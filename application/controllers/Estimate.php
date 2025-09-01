<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Estimate extends ClientsController
{
    public function index($id, $hash)
    {
        check_estimate_restrictions($id, $hash);
        $estimate = $this->estimates_model->get($id);

        if (!is_client_logged_in()) {
            load_client_language($estimate->clientid);
        }

        $identity_confirmation_enabled = get_option('estimate_accept_identity_confirmation');

        if ($this->input->post('estimate_action')) {
            $action = $this->input->post('estimate_action');

            // Only decline and accept allowed
            if ($action == 4 || $action == 3) {
                $success = $this->estimates_model->mark_action_status($action, $id, true);

                $redURL   = $this->uri->uri_string();
                $accepted = false;
                if (is_array($success) && $success['invoiced'] == true) {
                    $accepted = true;
                    $invoice  = $this->invoices_model->get($success['invoiceid']);
                    set_alert('success', _l('clients_estimate_invoiced_successfully'));
                    $redURL = site_url('invoice/' . $invoice->id . '/' . $invoice->hash);
                } elseif (is_array($success) && $success['invoiced'] == false || $success === true) {
                    if ($action == 4) {
                        $accepted = true;
                        set_alert('success', _l('clients_estimate_accepted_not_invoiced'));
                    } else {
                        set_alert('success', _l('clients_estimate_declined'));
                    }
                } else {
                    set_alert('warning', _l('clients_estimate_failed_action'));
                }
                if ($action == 4 && $accepted = true) {
                    process_digital_signature_image($this->input->post('signature', false), ESTIMATE_ATTACHMENTS_FOLDER . $id);

                    $this->db->where('id', $id);
                    $this->db->update(db_prefix() . 'estimates', get_acceptance_info_array());
                }
            }
            redirect($redURL);
        }
        // Handle Estimate PDF generator
        if ($this->input->post('estimatepdf')) {
            try {
                $pdf = estimate_pdf($estimate);
            } catch (Exception $e) {
                echo $e->getMessage();
                die;
            }

            $estimate_number = format_estimate_number($estimate->id);
            $companyname     = get_option('invoice_company_name');
            if ($companyname != '') {
                $estimate_number .= '-' . mb_strtoupper(slug_it($companyname), 'UTF-8');
            }

            $filename = hooks()->apply_filters('customers_area_download_estimate_filename', mb_strtoupper(slug_it($estimate_number), 'UTF-8') . '.pdf', $estimate);

            $pdf->Output($filename, 'D');
            die();
        }
        $this->load->library('app_number_to_word', [
            'clientid' => $estimate->clientid,
        ], 'numberword');

        $this->app_scripts->theme('sticky-js', 'assets/plugins/sticky/sticky.js');

        $data['title'] = format_estimate_number($estimate->id);
        $this->disableNavigation();
        $this->disableSubMenu();
        $data['hash']                          = $hash;
        $data['can_be_accepted']               = false;
        $data['estimate']                      = hooks()->apply_filters('estimate_html_pdf_data', $estimate);
        $data['bodyclass']                     = 'viewestimate';
        $data['identity_confirmation_enabled'] = $identity_confirmation_enabled;
        if ($identity_confirmation_enabled == '1') {
            $data['bodyclass'] .= ' identity-confirmation';
        }
        $this->data($data);
        $this->view('estimatehtml');
        add_views_tracking('estimate', $id);
        hooks()->do_action('estimate_html_viewed', $id);
        no_index_customers_area();
        $this->layout();
    }
    
    public function filters()
    {
        $this->_json_header();
        $data = $this->products->distinct_values();
        $data['settings'] = $this->settings->get_active();
        $this->_ok($data);
    }

    public function price()
    {
        $this->_json_header();

        $color     = $this->input->get_post('color', true);
        $finish    = $this->input->get_post('finish', true);
        $length    = (int)$this->input->get_post('length', true);
        $thickness = $this->input->get_post('thickness', true);

        if (!$color || !$finish || !$length || $thickness === null) {
            return $this->_fail('Missing filters');
        }

        $row = $this->products->find_item($color, $finish, $length, (float)$thickness);
        if (!$row) return $this->_fail('Item not found');

        $s = $this->settings->get_active();

        $rate = (float)$s['currency_rate_usd_to_inr'];
        $purchase_inr = $row['purchase_price_inr'];
        if ($purchase_inr === null || $purchase_inr === '') {
            $usd = (float)$row['purchase_price_usd'];
            $purchase_inr = $usd > 0 ? $usd * $rate : 0.0;
        } else {
            $purchase_inr = (float)$purchase_inr;
        }

        $margin = $row['margin_pct'] !== null ? (float)$row['margin_pct'] : (float)$s['default_margin_pct'];
        $addl   = $row['addl_market_margin_pct'] !== null ? (float)$row['addl_market_margin_pct'] : (float)$s['default_addl_market_margin_pct'];

        $price_after_margin = $purchase_inr * (1 + $margin/100.0);

        $satmat = $row['satmat_purchase'] !== null ? (float)$row['satmat_purchase'] : $price_after_margin * (1 + $addl/100.0);
        $metal  = $row['metal_market']    !== null ? (float)$row['metal_market']    : $satmat;

        $base = ($s['base_tier'] === 'satmat_purchase') ? $satmat : $metal;

        $tiers = [
            'regular_hardware' => round($base * (1 + (float)$s['pct_regular_hardware']/100.0)),
            'hardware'         => round($base * (1 + (float)$s['pct_hardware']/100.0)),
            'contractor'       => round($base * (1 + (float)$s['pct_contractor']/100.0)),
            'architect'        => round($base * (1 + (float)$s['pct_architect']/100.0)),
            'end_user'         => round($base * (1 + (float)$s['pct_end_user']/100.0)),
        ];

        $out = [
            'key' => [
                'color'=>$row['color'], 'finish'=>$row['finish'],
                'length_mm'=>(int)$row['length_mm'], 'thickness_mm'=>(float)$row['thickness_mm']
            ],
            'inputs' => [
                'purchase_price_usd' => (float)$row['purchase_price_usd'],
                'purchase_price_inr' => round($purchase_inr),
                'margin_pct' => $margin,
                'addl_market_margin_pct' => $addl,
                'currency_rate_usd_to_inr' => $rate,
            ],
            'computed' => [
                'satmat_purchase' => round($satmat),
                'metal_market'    => round($metal),
                'regular_hardware'=> $tiers['regular_hardware'],
                'hardware'        => $tiers['hardware'],
                'contractor'      => $tiers['contractor'],
                'architect'       => $tiers['architect'],
                'end_user'        => $tiers['end_user'],
            ]
        ];

        $this->_ok($out);
    }

    public function update_settings()
    {
        $this->_json_header();
        $payload = json_decode($this->input->raw_input_stream, true);
        if (!$payload) return $this->_fail('Invalid JSON');

        // allowlist fields
        $fields = [
            'currency_rate_usd_to_inr', 'default_margin_pct', 'default_addl_market_margin_pct',
            'pct_regular_hardware', 'pct_hardware', 'pct_contractor', 'pct_architect', 'pct_end_user',
            'base_tier'
        ];
        $data = [];
        foreach ($fields as $f) {
            if (array_key_exists($f, $payload)) $data[$f] = $payload[$f];
        }
        if (!$data) return $this->_fail('No fields to update');

        $id = $this->settings->upsert($data);
        $this->_ok(['updated'=>true, 'id'=>$id]);
    }


    private function _json_header()
    {
        $this->output->set_content_type('application/json')->set_header('Cache-Control: no-store');
    }
    private function _ok($data)   { $this->output->set_output(json_encode(['ok'=>true, 'data'=>$data])); }
    private function _fail($msg)  { $this->output->set_output(json_encode(['ok'=>false,'message'=>$msg])); }

    
    
}
