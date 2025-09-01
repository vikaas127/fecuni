<?php defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . '_sheet_customer_grp_discount.group_id',  
    db_prefix() . '_sheet_customer_grp_discount.sheet_id',   
    db_prefix() . '_sheet_customer_grp_discount.discount',
    db_prefix() . '_sheet_customer_grp_discount.margin',
    db_prefix() . '_sheet_customer_grp_discount.override',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . '_sheet_customer_grp_discount';

$join = [
    'LEFT JOIN ' . db_prefix() . 'customers_groups ON ' . db_prefix() . 'customers_groups.id = ' . db_prefix() . '_sheet_customer_grp_discount.group_id',
    'LEFT JOIN ' . db_prefix() . 'wh_sheet ON ' . db_prefix() . 'wh_sheet.sheet_type_id = ' . db_prefix() . '_sheet_customer_grp_discount.sheet_id'
];

$additionalSelect = [
    db_prefix() . 'customers_groups.id as group_id',          // numeric ID
    db_prefix() . 'wh_sheet.sheet_type_id as sheet_id',       // numeric ID
    db_prefix() . 'customers_groups.name as group_name',     // name to show
    db_prefix() . 'wh_sheet.sheet_name as sheet_name',        // name to show
    db_prefix() . '_sheet_customer_grp_discount.discount',
    db_prefix() . '_sheet_customer_grp_discount.margin',
    db_prefix() . '_sheet_customer_grp_discount.override',
    db_prefix() . '_sheet_customer_grp_discount.id'
];

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, [], $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    // Group name column with data-id for modal
    $row[] = '<span data-id="' . $aRow['group_id'] . '" data-name="' . htmlspecialchars($aRow['group_name'], ENT_QUOTES) . '">' . $aRow['group_name'] . '</span>';

    // Sheet name column with data-id for modal
    $row[] = '<span data-id="' . $aRow['sheet_id'] . '" data-name="' . htmlspecialchars($aRow['sheet_name'], ENT_QUOTES) . '">' . $aRow['sheet_name'] . '</span>';

    // Discount and Margin
    $row[] = $aRow['discount'] . '%';
    $row[] = $aRow['margin'] . '%';

    // Override
    $row[] = '<span data-val="' . $aRow['override'] . '">' . ($aRow['override'] ? _l('yes') : _l('no')) . '</span>';

    // Actions
    $options = '<div class="tw-flex tw-items-center tw-space-x-3">';
    $options .= '<a href="#" data-toggle="modal" data-target="#customer_sheet_discount_modal" data-id="' . $aRow['id'] . '">
                    <i class="fas fa-pen-to-square fa-lg"></i>
                 </a>';
    $options .= '<a href="' . admin_url('clients/delete_sheet_customer_grp_discount/' . $aRow['id']) . '" class="_delete">
                    <i class="fas fa-trash-can fa-lg"></i>
                 </a>';
    $options .= '</div>';

    $row[] = $options;

    $output['aaData'][] = $row;
}
