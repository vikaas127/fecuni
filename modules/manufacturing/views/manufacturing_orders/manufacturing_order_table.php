<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Define aliased columns to prevent ambiguity
$aColumns = [
    'mo.id as mo_id',
    'mo.manufacturing_order_code',
    'mo.product_id',
	'material.material_name as material_name',
	'profile.profile_name as profile_name',
	'items.Thickness as Thickness',
	'colors.color_name as color_name',
	'finish.finish_name as finish_name',
    'mo.bom_id',
    'mo.product_qty',
    'mo.produced_qty',

    'mo.unit_id',
    'mo.routing_id',
    'mo.status as mo_status',
    'mo.contact_id'
];

$sIndexColumn = 'mo_id';
$sTable = db_prefix() . 'mrp_manufacturing_orders as mo';

$where = [];
$join[] = 'LEFT JOIN '.db_prefix().'clients as c ON mo.contact_id = c.userid';
$join[] = 'LEFT JOIN ' . db_prefix() . 'items AS items ON mo.product_id = items.id';
$join[] = 'LEFT JOIN ' . db_prefix() . 'mrp_material AS material ON items.material_type_id = material.material_type_id';
$join[] = 'LEFT JOIN ' . db_prefix() . 'mrp_profile AS profile ON items.profile_type_id = profile.profile_type_id';
$join[] = 'LEFT JOIN ' . db_prefix() . 'mrp_finish AS finish ON items.finish_type_id = finish.finish_type_id';
$join[] = 'LEFT JOIN ' . db_prefix() . 'ware_color AS colors ON items.color = colors.color_id';

$products_filter = $this->ci->input->post('products_filter');
$routing_filter = $this->ci->input->post('routing_filter');
$status_filter = $this->ci->input->post('status_filter');
$customer_filter = $this->ci->input->post('customer_filter');
$material_filter = $this->ci->input->post('material_filter');
$finish_filter = $this->ci->input->post('finish_filter');
$profile_filter = $this->ci->input->post('profile_filter');
$color_filter = $this->ci->input->post('color_filter');
$thickness_filter = $this->ci->input->post('thickness_filter');

// Customer filter
if (isset($customer_filter)) {
    $where_customer_ft = '';
    foreach ($customer_filter as $contact_id) {
        if ($contact_id != '') {
            if ($where_customer_ft == '') {
                $where_customer_ft .= 'AND (mo.contact_id = "' . $contact_id . '"';
            } else {
                $where_customer_ft .= ' OR mo.contact_id = "' . $contact_id . '"';
            }
        }
    }
    if ($where_customer_ft != '') {
        $where_customer_ft .= ')';
        $where[] = $where_customer_ft;
    }
}

// Product filter
if (isset($products_filter)) {
    $where_products_ft = '';
    foreach ($products_filter as $product_id) {
        if ($product_id != '') {
            if ($where_products_ft == '') {
                $where_products_ft .= 'AND (mo.product_id = "' . $product_id . '"';
            } else {
                $where_products_ft .= ' OR mo.product_id = "' . $product_id . '"';
            }
        }
    }
    if ($where_products_ft != '') {
        $where_products_ft .= ')';
        $where[] = $where_products_ft;
    }
}

// Routing filter
if (isset($routing_filter)) {
    $where_routing_ft = '';
    foreach ($routing_filter as $routing_id) {
        if ($routing_id != '') {
            if ($where_routing_ft == '') {
                $where_routing_ft .= 'AND (mo.routing_id = "' . $routing_id . '"';
            } else {
                $where_routing_ft .= ' OR mo.routing_id = "' . $routing_id . '"';
            }
        }
    }
    if ($where_routing_ft != '') {
        $where_routing_ft .= ')';
        $where[] = $where_routing_ft;
    }
}

// Status filter
if (isset($status_filter)) {
    $where_status_ft = '';
    foreach ($status_filter as $status) {
        if ($status != '') {
            if ($where_status_ft == '') {
                $where_status_ft .= 'AND (mo.mo_status = "' . $status . '"';
            } else {
                $where_status_ft .= ' OR mo.mo_status = "' . $status . '"';
            }
        }
    }
    if ($where_status_ft != '') {
        $where_status_ft .= ')';
        $where[] = $where_status_ft;
    }
}
// MATERIAL FILTER
if (!empty($material_filter)) {
	$where_material_filter = '';
	foreach ($material_filter as $material_id) {
		if ($material_id != '') {
			$where_material_filter .= ($where_material_filter == '' ? 'AND (' : ' OR ') . 'material.material_type_id = "' . $material_id . '"';
		}
	}
	if ($where_material_filter != '') {
		$where_material_filter .= ')';
		$where[] = $where_material_filter;
	}
}
if (!empty($thickness_filter)) {
	$where_thickness_filter = '';
	foreach ($thickness_filter as $thickness) {
		if ($thickness != '') {
			$where_thickness_filter .= ($where_thickness_filter == '' ? 'AND (' : ' OR ') . 'items.Thickness = "' . $thickness . '"';
		}
	}
	if ($where_thickness_filter != '') {
		$where_thickness_filter .= ')';
		$where[] = $where_thickness_filter;
	}
}


// COLOR FILTER
if (!empty($color_filter)) {
	$where_color_filter = '';
	foreach ($color_filter as $color_id) {
		if ($color_id != '') {
			$where_color_filter .= ($where_color_filter == '' ? 'AND (' : ' OR ') . 'items.color = "' . $color_id . '"';
		}
	}
	if ($where_color_filter != '') {
		$where_color_filter .= ')';
		$where[] = $where_color_filter;
	}
}


// FINISH FILTER
if (!empty($finish_filter)) {
	$where_finish_filter = '';
	foreach ($finish_filter as $finish_id) {
		if ($finish_id != '') {
			$where_finish_filter .= ($where_finish_filter == '' ? 'AND (' : ' OR ') . 'items.finish_type_id = "' . $finish_id . '"';
		}
	}
	if ($where_finish_filter != '') {
		$where_finish_filter .= ')';
		$where[] = $where_finish_filter;
	}
}

// PROFILE FILTER
if (!empty($profile_filter)) {
	$where_profile_filter = '';
	foreach ($profile_filter as $profile_id) {
		if ($profile_id != '') {
			$where_profile_filter .= ($where_profile_filter == '' ? 'AND (' : ' OR ') . 'profile.profile_type_id = "' . $profile_id . '"';
		}
	}
	if ($where_profile_filter != '') {
		$where_profile_filter .= ')';
		$where[] = $where_profile_filter;
	}
}



// DataTables initialization
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['mo.id']);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['mo_id'] . '"><label></label></div>';

    foreach ($aColumns as $col) {
        $col_name = trim(explode(' as ', $col)[1] ?? explode('.', $col)[1]);
        $_data = '';

        switch ($col_name) {
            case 'mo_id':
                $_data = $aRow['mo_id'];
                break;

            case 'manufacturing_order_code':
                $code = '<a href="' . admin_url('manufacturing/view_manufacturing_order/' . $aRow['mo_id']) . '">' . $aRow['manufacturing_order_code'] . '</a>';
                $code .= '<div class="row-options">';
                $code .= '<a href="' . admin_url('manufacturing/view_manufacturing_order/' . $aRow['mo_id']) . '">' . _l('view') . '</a>';
                if ((has_permission('manufacturing', '', 'edit') && has_permission('manufacturing_orders', '', 'edit')) || is_admin()) {
                    $code .= ' | <a href="' . admin_url('manufacturing/add_edit_manufacturing_order/' . $aRow['mo_id']) . '">' . _l('edit') . '</a>';
                }
                if ((has_permission('manufacturing', '', 'delete') && has_permission('manufacturing_orders', '', 'delete')) || is_admin()) {
                    $code .= ' | <a href="' . admin_url('manufacturing/delete_manufacturing_order/' . $aRow['mo_id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                }
                $code .= '</div>';
                $_data = $code;
                break;

            case 'product_id':
                $_data = mrp_get_product_name($aRow['product_id']);
				
				break;
			case 'material_name':
				$_data = $aRow['material_name'];

				break;
			case 'profile_name':
				$_data = $aRow['profile_name'];

				break;
			case 'Thickness':
				$_data = $aRow['Thickness'];
				break;
			case 'color_name':
				$_data = $aRow['color_name'];
				break;	
			case 'finish_name':
				$_data = $aRow['finish_name'];
                break;

            case 'bom_id':
                $_data = mrp_get_bill_of_material_code($aRow['bom_id']) . ' ' . mrp_get_product_name(mrp_get_bill_of_material($aRow['bom_id']));
                break;

            case 'product_qty':
                $_data = app_format_money($aRow['product_qty'], '');
                break;
            case 'produced_qty':
                $_data = app_format_money($aRow['produced_qty'], '');
                break;

            case 'unit_id':
                $_data = mrp_get_unit_name($aRow['unit_id']);
                break;

            case 'routing_id':
                $_data = mrp_get_routing_name($aRow['routing_id']);
                break;

            case 'mo_status':
                $_data = '<span class="label label-' . $aRow['mo_status'] . '">' . _l($aRow['mo_status']) . '</span>';

                if ($aRow['mo_status'] === 'done') {
                    $produced = (float)$aRow['produced_qty'];
                    $required = (float)$aRow['product_qty'];

                    if ($produced > $required) {
                        $excess = $produced - $required;
                        $_data .= '<br><span class="text-success">Excess: ' . $excess . '</span>';
                    } else {
                        $remaining = $required - $produced;
                        $_data .= '<br><span class="text-danger">Remaining: ' . $remaining . '</span>';
                    }
                }
                break;


            case 'contact_id':
                $_data = get_relation_values(get_relation_data('customer', $aRow['contact_id']), 'customer')['name'];
                break;
        }

        $row[] = $_data;
    }

    $output['aaData'][] = $row;
}
