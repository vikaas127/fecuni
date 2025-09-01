<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_items extends AdminController
{
    private $not_importable_fields = ['id'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoice_items_model');
    }

    /* List all available items */
    public function index()
    {
        if (staff_cant('view', 'items')) {
            access_denied('Invoice Items');
        }

        $this->load->model('taxes_model');
        $data['taxes']        = $this->taxes_model->get();
        $data['items_groups'] = $this->invoice_items_model->get_groups();

        $this->load->model('currencies_model');
        $data['currencies'] = $this->currencies_model->get();

        $data['base_currency'] = $this->currencies_model->get_base_currency();

        $data['title'] = _l('invoice_items');
        $this->load->view('admin/invoice_items/manage', $data);
    }

    public function table()
    {
        if (staff_cant('view', 'items')) {
            ajax_access_denied();
        }
        $this->app->get_table_data('invoice_items');
    }

    /* Edit or update items / ajax request /*/
    public function manage()
    {
        if (staff_can('view',  'items')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if ($data['itemid'] == '') {
                    if (staff_cant('create', 'items')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $id      = $this->invoice_items_model->add($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfully', _l('sales_item'));
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                        'item'    => $this->invoice_items_model->get($id),
                    ]);
                } else {
                    if (staff_cant('edit', 'items')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $success = $this->invoice_items_model->edit($data);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfully', _l('sales_item'));
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                    ]);
                }
            }
        }
    }




 


// Display the quantity discount page
public function sheet_qty_discount_setting()
{
    $this->load->model('invoice_items_model');
    $data['discounts'] = $this->invoice_items_model->get_quantity_discounts();
    $data['sheets'] = $this->invoice_items_model->get_sheets();
    $this->load->view('admin/invoice_items/sheet_qty_discount_setting', $data);
}

// Save discount
public function save_sheet_qty_discount()
{
    $this->load->model('invoice_items_model');
    $post = $this->input->post();

    $data = [
        'sheet_type_id' => $post['sheet_type_id'],
        'min_qty' => $post['min_qty'],
        'max_qty' => $post['max_qty'] ?: NULL,
        'discount_type' => $post['discount_type'],
        'discount_value' => $post['discount_value'],
        'active' => isset($post['active']) ? 1 : 0
    ];

    if (!empty($post['id'])) {
        $data['id'] = $post['id'];
    }

    $this->invoice_items_model->save_quantity_discount($data);
    set_alert('success', _l('updated_successfully'));
    redirect(admin_url('invoice_items/sheet_qty_discount_setting'));
}

// Delete discount
public function delete_sheet_qty_discount($id)
{
    $this->load->model('invoice_items_model');
    $this->invoice_items_model->delete_quantity_discount($id);
    set_alert('success', _l('deleted_successfully'));
    redirect(admin_url('invoice_items/sheet_qty_discount_setting'));
}

    public function import()
    {
        if (staff_cant('create', 'items')) {
            access_denied('Items Import');
        }

        $this->load->library('import/import_items', [], 'import');

        $this->import->setDatabaseFields($this->db->list_fields(db_prefix() . 'items'))
            ->setCustomFields(get_custom_fields('items'));

        if ($this->input->post('download_sample') === 'true') {
            $this->import->downloadSample();
        }

        if (
            $this->input->post()
            && isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != ''
        ) {
            $this->import->setSimulation($this->input->post('simulate'))
                ->setTemporaryFileLocation($_FILES['file_csv']['tmp_name'])
                ->setFilename($_FILES['file_csv']['name'])
                ->perform();

            $data['total_rows_post'] = $this->import->totalRows();

            if (!$this->import->isSimulation()) {
                set_alert('success', _l('import_total_imported', $this->import->totalImported()));
            }
        }

        $data['title'] = _l('import');
        $this->load->view('admin/invoice_items/import', $data);
    }

    public function add_group()
    {
        if ($this->input->post() && staff_can('create',  'items')) {
            $this->invoice_items_model->add_group($this->input->post());
            set_alert('success', _l('added_successfully', _l('item_group')));
        }
    }

    public function update_group($id)
    {
        if ($this->input->post() && staff_can('edit',  'items')) {
            $this->invoice_items_model->edit_group($this->input->post(), $id);
            set_alert('success', _l('updated_successfully', _l('item_group')));
        }
    }

    public function delete_group($id)
    {
        if (staff_can('delete',  'items')) {
            if ($this->invoice_items_model->delete_group($id)) {
                set_alert('success', _l('deleted', _l('item_group')));
            }
        }
        redirect(admin_url('invoice_items?groups_modal=true'));
    }

    /* Delete item*/
    public function delete($id)
    {
        if (staff_cant('delete', 'items')) {
            access_denied('Invoice Items');
        }

        if (!$id) {
            redirect(admin_url('invoice_items'));
        }

        $response = $this->invoice_items_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('invoice_item_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('invoice_item')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoice_item_lowercase')));
        }
        redirect(admin_url('invoice_items'));
    }

    public function bulk_action()
    {
        hooks()->do_action('before_do_bulk_action_for_items');
        $total_deleted = 0;
        if ($this->input->post()) {
            $ids                   = $this->input->post('ids');
            $has_permission_delete = staff_can('delete',  'items');
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    if ($this->input->post('mass_delete')) {
                        if ($has_permission_delete) {
                            if ($this->invoice_items_model->delete($id)) {
                                $total_deleted++;
                            }
                        }
                    }
                }
            }
        }

        if ($this->input->post('mass_delete')) {
            set_alert('success', _l('total_items_deleted', $total_deleted));
        }
    }

    public function search()
    {
        if ($this->input->post() && $this->input->is_ajax_request()) {
            echo json_encode($this->invoice_items_model->search($this->input->post('q')));
        }
    }

    /* Get item by id / ajax */
    public function get_item_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $item                     = $this->invoice_items_model->get($id);
            $item->long_description   = nl2br($item->long_description);
            $item->custom_fields_html = render_custom_fields('items', $id, [], ['items_pr' => true]);
            $item->custom_fields      = [];

            $cf = get_custom_fields('items');

            foreach ($cf as $custom_field) {
                $val = get_custom_field_value($id, $custom_field['id'], 'items_pr');
                if ($custom_field['type'] == 'textarea') {
                    $val = clear_textarea_breaks($val);
                }
                $custom_field['value'] = $val;
                $item->custom_fields[] = $custom_field;
            }

            echo json_encode($item);
        }
    }





public function filter() {
    $sheet = $this->input->post('sheet');
    $length = $this->input->post('length');
    $thickness = $this->input->post('thickness');
    $color = $this->input->post('color');
    $finish = $this->input->post('finish');


    $this->load->model('purchase_model'); // or your items model
    $items = $this->purchase_model->get_filtered_items($sheet, $length, $thickness, $color, $finish);

    // group items by group_id for optgroup
    $grouped = [];
    foreach($items as $item){
        $grouped[$item['group_id']]['group_name'] = $item['group_name'];
        $grouped[$item['group_id']]['group_id'] = $item['group_id'];
        $grouped[$item['group_id']]['items'][] = $item;
    }

    echo json_encode(array_values($grouped));
}


    /* Copy Item */
    public function copy($id)
    {
        if (staff_cant('create', 'items')) {
            access_denied('Create Item');
        }

        $data = (array) $this->invoice_items_model->get($id);

        $id = $this->invoice_items_model->copy($data);

        if ($id) {
            set_alert('success', _l('item_copy_success'));
            return redirect(admin_url('invoice_items?id=' . $id));
        }

        set_alert('warning', _l('item_copy_fail'));
        return redirect(admin_url('invoice_items'));
    }
}
