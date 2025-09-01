<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'wo.id as wo_id',
    'wo.operation_name',
    'wo.date_planned_start',
    'wo.work_center_id',
    'wo.manufacturing_order_id',
    'wo.product_id',
	'material.material_name as material_name',
	'profile.profile_name as profile_name',
	'items.Thickness as Thickness',
	'colors.color_name as color_name',
	'finish.finish_name as finish_name',
    'wo.qty_production',
    'wo.unit_id',
    'wo.status as wo_status',
    'wo.contact_id'
];

$sIndexColumn = 'wo_id';
$sTable = db_prefix() . 'mrp_work_orders as wo';

$where = [];
$join[] = 'LEFT JOIN ' . db_prefix() . 'clients as c ON wo.contact_id = c.userid';
$join[] = 'LEFT JOIN ' . db_prefix() . 'items AS items ON wo.product_id = items.id';
$join[] = 'LEFT JOIN ' . db_prefix() . 'mrp_material AS material ON items.material_type_id = material.material_type_id';
$join[] = 'LEFT JOIN ' . db_prefix() . 'mrp_profile AS profile ON items.profile_type_id = profile.profile_type_id';
$join[] = 'LEFT JOIN ' . db_prefix() . 'mrp_finish AS finish ON items.finish_type_id = finish.finish_type_id';
$join[] = 'LEFT JOIN ' . db_prefix() . 'ware_color AS colors ON items.color = colors.color_id';

$manufacturing_order_filter = $this->ci->input->post('manufacturing_order_filter');
$products_filter = $this->ci->input->post('products_filter');
$status_filter = $this->ci->input->post('status_filter');
$customer_filter = $this->ci->input->post('customer_filter');
$material_filter = $this->ci->input->post('material_filter');
$finish_filter = $this->ci->input->post('finish_filter');
$profile_filter = $this->ci->input->post('profile_filter');
$color_filter = $this->ci->input->post('color_filter');
$thickness_filter = $this->ci->input->post('thickness_filter');
// CUSTOMER FILTER
if (isset($customer_filter)) {
    $where_customer_ft = '';
    foreach ($customer_filter as $contact_id) {
        if ($contact_id != '') {
            $where_customer_ft .= ($where_customer_ft == '' ? 'AND (' : ' OR ') . 'wo.contact_id = "' . $contact_id . '"';
        }
    }
    if ($where_customer_ft != '') {
        $where_customer_ft .= ')';
        $where[] = $where_customer_ft;
    }
}

// MANUFACTURING ORDER FILTER
if (isset($manufacturing_order_filter)) {
    $where_manufacturing_order_ft = '';
    foreach ($manufacturing_order_filter as $mo_id) {
        if ($mo_id != '') {
            $where_manufacturing_order_ft .= ($where_manufacturing_order_ft == '' ? 'AND (' : ' OR ') . 'wo.manufacturing_order_id = "' . $mo_id . '"';
        }
    }
    if ($where_manufacturing_order_ft != '') {
        $where_manufacturing_order_ft .= ')';
        $where[] = $where_manufacturing_order_ft;
    }
}

// PRODUCT FILTER
if (isset($products_filter)) {
    $where_products_ft = '';
    foreach ($products_filter as $product_id) {
        if ($product_id != '') {
            $where_products_ft .= ($where_products_ft == '' ? 'AND (' : ' OR ') . 'wo.product_id = "' . $product_id . '"';
        }
    }
    if ($where_products_ft != '') {
        $where_products_ft .= ')';
        $where[] = $where_products_ft;
    }
}

// STATUS FILTER
if (isset($status_filter)) {
    $where_status_ft = '';
    foreach ($status_filter as $status) {
        if ($status != '') {
            $where_status_ft .= ($where_status_ft == '' ? 'AND (' : ' OR ') . 'wo.status = "' . $status . '"';
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



// PERMISSION-BASED FILTER
$logged_in_staff_id = get_staff_user_id();
$has_view_permission = has_permission('work_order', '', 'view');
$has_view_own_permission = has_permission('work_order', '', 'view_own');

log_message('info', 'User ID: ' . $logged_in_staff_id . ' | View Permission: ' . ($has_view_permission ? 'Yes' : 'No') . ' | View Own Permission: ' . ($has_view_own_permission ? 'Yes' : 'No'));

if ($has_view_own_permission && !$has_view_permission) {
    $where_condition = 'AND wo.id IN (
        SELECT wo_inner.id 
        FROM ' . db_prefix() . 'mrp_work_orders as wo_inner
        JOIN ' . db_prefix() . 'mrp_routing_details as rd 
        ON wo_inner.routing_detail_id = rd.id
        WHERE rd.staff_id = ' . $logged_in_staff_id . '
    )';

    log_message('info', 'Applying View Own Permission Filter: ' . $where_condition);
    $where[] = $where_condition;
} else {
    log_message('info', 'User has global view access to all work orders.');
}

// FETCH DATA
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['wo.id']);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    foreach ($aColumns as $col) {
        $col_name = trim(explode(' as ', $col)[1] ?? explode('.', $col)[1]);
        $_data = '';

        switch ($col_name) {
            case 'wo_id':
                $_data = $aRow['wo_id'];
                break;

            case 'operation_name':
                $_data = '<a href="' . admin_url('manufacturing/view_work_order/' . $aRow['wo_id'] . '/' . $aRow['manufacturing_order_id']) . '">' . $aRow['operation_name'] . '</a>';
                $_data .= '<div class="row-options">';
                $_data .= '<a href="' . admin_url('manufacturing/view_work_order/' . $aRow['wo_id'] . '/' . $aRow['manufacturing_order_id']) . '">' . _l('view') . '</a>';
                $_data .= '</div>';
                break;

            case 'date_planned_start':
                $_data = _dt($aRow['date_planned_start']);
                break;

            case 'work_center_id':
                $_data = get_work_center_name($aRow['work_center_id']);
                break;

            case 'manufacturing_order_id':
                $_data = mrp_get_manufacturing_code($aRow['manufacturing_order_id']);
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

            case 'qty_production':
                $_data = app_format_money($aRow['qty_production'], '');
                break;

            case 'unit_id':
                $_data = mrp_get_unit_name($aRow['unit_id']);
                break;

            case 'wo_status':
                $_data = '<span class="label label-' . $aRow['wo_status'] . '">' . _l($aRow['wo_status']) . '</span>';
                break;

            case 'contact_id':
                $_data = get_relation_values(get_relation_data('customer', $aRow['contact_id']), 'customer')['name'];
                break;
        }

        $row[] = $_data;
    }

    $output['aaData'][] = $row;
}
