<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Add the material_name column with alias
$aColumns = [
	'bom.id',
	'bom.product_id',
	'material.material_name as material_name',
	'profile.profile_name as profile_name',
	'items.Thickness as Thickness',
	'colors.color_name as color_name',
	'finish.finish_name as finish_name',
      

	'bom.bom_code',
	'bom.bom_type',
	'bom.product_variant_id',
	'bom.product_qty',
	'bom.unit_id',
	'bom.routing_id',
];

$sIndexColumn = 'bom.id';
$sTable = db_prefix() . 'mrp_bill_of_materials AS bom';

$where = [];
$join = [];

// Add joins
$join[] = 'LEFT JOIN ' . db_prefix() . 'items AS items ON bom.product_id = items.id';
$join[] = 'LEFT JOIN ' . db_prefix() . 'mrp_material AS material ON items.material_type_id = material.material_type_id';
$join[] = 'LEFT JOIN ' . db_prefix() . 'mrp_profile AS profile ON items.profile_type_id = profile.profile_type_id';
$join[] = 'LEFT JOIN ' . db_prefix() . 'mrp_finish AS finish ON items.finish_type_id = finish.finish_type_id';
$join[] = 'LEFT JOIN ' . db_prefix() . 'ware_color AS colors ON items.color = colors.color_id';




$products_filter = $this->ci->input->post('products_filter');
$bom_type_filter = $this->ci->input->post('bom_type_filter');
$routing_filter = $this->ci->input->post('routing_filter');

$material_filter = $this->ci->input->post('material_filter');
$finish_filter = $this->ci->input->post('finish_filter');
$profile_filter = $this->ci->input->post('profile_filter');
$color_filter = $this->ci->input->post('color_filter');
$thickness_filter = $this->ci->input->post('thickness_filter');



// --- PRODUCT FILTER ---
if (isset($products_filter)) {
	$products = $this->ci->manufacturing_model->bom_get_product_filter($products_filter);
	$where_products_filter = '';
	foreach ($products as $product) {
		if ($where_products_filter == '') {
			if (isset($product['parent_id']) && $product['parent_id'] != 0) {
				$where_products_filter .= "AND (((bom.product_id = {$product['parent_id']} AND bom.product_variant_id = {$product['id']}) OR (bom.product_id = {$product['parent_id']} AND (bom.product_variant_id = 0 OR bom.product_variant_id IS NULL)))";
			} else {
				$where_products_filter .= "AND (bom.product_id = {$product['id']}";
			}
		} else {
			if (isset($product['parent_id']) && $product['parent_id'] != 0) {
				$where_products_filter .= " OR ((bom.product_id = {$product['parent_id']} AND bom.product_variant_id = {$product['id']}) OR (bom.product_id = {$product['parent_id']} AND (bom.product_variant_id = 0 OR bom.product_variant_id IS NULL)))";
			} else {
				$where_products_filter .= " OR bom.product_id = {$product['id']}";
			}
		}
	}
	if ($where_products_filter != '') {
		$where_products_filter .= ')';
		$where[] = $where_products_filter;
	}
}

// --- BOM TYPE FILTER ---
if (isset($bom_type_filter)) {
	$where_bom_type_filter = '';
	foreach ($bom_type_filter as $bom_type) {
		if ($bom_type != '') {
			if ($where_bom_type_filter == '') {
				$where_bom_type_filter .= 'AND (bom.bom_type = "' . $bom_type . '"';
			} else {
				$where_bom_type_filter .= ' OR bom.bom_type = "' . $bom_type . '"';
			}
		}
	}
	if ($where_bom_type_filter != '') {
		$where_bom_type_filter .= ')';
		$where[] = $where_bom_type_filter;
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


// --- ROUTING FILTER ---
if (isset($routing_filter)) {
	$where_routing_filter = '';
	foreach ($routing_filter as $routing_id) {
		if ($routing_id != '') {
			if ($where_routing_filter == '') {
				$where_routing_filter .= 'AND (bom.routing_id = "' . $routing_id . '"';
			} else {
				$where_routing_filter .= ' OR bom.routing_id = "' . $routing_id . '"';
			}
		}
	}
	if ($where_routing_filter != '') {
		$where_routing_filter .= ')';
		$where[] = $where_routing_filter;
	}
}

// Get the result
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['bom.id']);
$output = $result['output'];
$rResult = $result['rResult'];

// Build each row
foreach ($rResult as $aRow) {
	$row = [];

	// Checkbox column
	$row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';

	foreach ($aColumns as $col) {
		switch ($col) {
			case 'bom.id':
				$_data = $aRow['id'];
				break;

			case 'bom.product_id':
				$code = '<a href="' . admin_url('manufacturing/bill_of_material_detail_manage/' . $aRow['id']) . '">' . mrp_get_product_name($aRow['product_id']) . '</a>';
				$code .= '<div class="row-options">';
				$code .= '<a href="' . admin_url('manufacturing/view_bill_of_material_detail/' . $aRow['id']) . '">' . _l('view') . '</a>';

				if ((has_permission('manufacturing', '', 'edit') && has_permission('bill_of_material', '', 'edit')) || is_admin()) {
					$code .= ' | <a href="' . admin_url('manufacturing/bill_of_material_detail_manage/' . $aRow['id']) . '">' . _l('edit') . '</a>';
				}

				if ((has_permission('manufacturing', '', 'delete') && has_permission('bill_of_material', '', 'delete')) || is_admin()) {
					$code .= ' | <a href="' . admin_url('manufacturing/delete_bill_of_material/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
				}

				$code .= ' | <a href="#" onclick="copyBOM(' . $aRow['id'] . '); return false;">' . _l('copy_BOM') . '</a>';
				$code .= '</div>';
				$_data = $code;

				break;
			case 'material.material_name as material_name':
				$_data = $aRow['material_name'];

				break;
			case 'profile.profile_name as profile_name':
				$_data = $aRow['profile_name'];

				break;
			case 'items.Thickness as Thickness':
				$_data = $aRow['Thickness'];
				break;
			case 'colors.color_name as color_name':
				$_data = $aRow['color_name'];
				break;	
			case 'finish.finish_name as finish_name':
				$_data = $aRow['finish_name'];

				break;
			case 'bom.bom_code':
				$_data = $aRow['bom_code'];
				break;

			case 'bom.bom_type':
				$_data = _l($aRow['bom_type']);
				break;

			case 'bom.product_variant_id':
				$_data = mrp_get_product_name($aRow['product_variant_id']);
				break;

			case 'bom.product_qty':
				$_data = app_format_money($aRow['product_qty'], '');
				break;

			case 'bom.unit_id':
				$_data = mrp_get_unit_name($aRow['unit_id']);
				break;

			case 'bom.routing_id':
				$_data = mrp_get_routing_name($aRow['routing_id']);
				break;

			

			default:
				$_data = isset($aRow[$col]) ? $aRow[$col] : '';
				break;
		}

		$row[] = $_data;
	}

	$output['aaData'][] = $row;
}
