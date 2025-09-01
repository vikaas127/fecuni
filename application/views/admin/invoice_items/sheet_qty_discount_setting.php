<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
                        <div class="col-md-12">

<div>
    <div class="_buttons">
        <?php if (has_permission('wh_setting', '', 'create') || is_admin()) { ?>
        <a href="#" onclick="new_discount(); return false;" class="btn btn-info pull-left display-block">
            <?php echo _l('add_quantity_discount'); ?>
        </a>
        <?php } ?>
    </div>
    <div class="clearfix"></div>
    <hr class="hr-panel-heading" />
    <div class="clearfix"></div>

    <table class="table dt-table border table-striped">
        <thead>
            <th><?php echo _l('sheet_name'); ?></th>
            <th><?php echo _l('min_qty'); ?></th>
            <th><?php echo _l('max_qty'); ?></th>
            <th><?php echo _l('discount_type'); ?></th>
            <th><?php echo _l('discount_value'); ?></th>
            <th><?php echo _l('active'); ?></th>
            <th><?php echo _l('options'); ?></th>
        </thead>
      <tbody>
<?php foreach($discounts as $d){ ?>
<tr>
    <td><?php echo _l($d['sheet_name']); ?></td>
    <td><?php echo (int)$d['min_qty']; ?></td> <!-- show as integer -->
    <td><?php echo $d['max_qty'] !== null ? (int)$d['max_qty'] : '∞'; ?></td> <!-- integer or infinity -->
    <td><?php echo _l($d['discount_type']); ?></td>
    <td>
        <?php 
            // round discount_value to 2 decimals
            echo number_format($d['discount_value'], 2, '.', '');
        ?>
    </td>
    <td><?php echo ($d['active'] ? _l('active') : _l('inactive')); ?></td>
    <td>
        <?php if (has_permission('wh_setting', '', 'edit') || is_admin()) { ?>
        <a href="#" onclick="edit_discount(this, <?php echo $d['id']; ?>); return false;"
           data-sheet_type_id="<?php echo $d['sheet_type_id']; ?>"
           data-min_qty="<?php echo $d['min_qty']; ?>"
           data-max_qty="<?php echo $d['max_qty']; ?>"
           data-discount_type="<?php echo $d['discount_type']; ?>"
           data-discount_value="<?php echo $d['discount_value']; ?>"
           data-active="<?php echo $d['active']; ?>"
           class="btn btn-default btn-icon"><i class="fa-regular fa-pen-to-square"></i></a>
        <?php } ?>

        <a href="<?php echo admin_url('invoice_items/delete_sheet_qty_discount/'.$d['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
    </td>
</tr>
<?php } ?>
</tbody>

    </table>
</div>

<div class="modal fade" id="discount_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog setting-handsome-table">
        <?php echo form_open(admin_url('invoice_items/save_sheet_qty_discount'), array('id'=>'discount_form')); ?>

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <span class="add-title"><?php echo _l('add_quantity_discount'); ?></span>
                    <span class="edit-title"><?php echo _l('edit_quantity_discount'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="id" id="discount_id">
                    <div class="col-md-6">
                        <label><?php echo _l('sheet_name'); ?></label>
                        <select name="sheet_type_id" id="sheet_type_id" class="form-control" required>
                            <option value=""><?php echo _l('select_sheet'); ?></option>
                            <?php foreach($sheets as $sheet){ ?>
                                <option value="<?php echo $sheet['sheet_type_id']; ?>"><?php echo $sheet['sheet_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <?php echo render_input('min_qty','min_qty','0','number',['min'=>'0','step'=>'0.0001']); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo render_input('max_qty','max_qty','','number',['min'=>'0','step'=>'0.0001']); ?>
                    </div>
                    <div class="col-md-6">
                        <label><?php echo _l('discount_type'); ?></label>
                        <select name="discount_type" id="discount_type" class="form-control" required>
                            <option value="percent"><?php echo _l('percent'); ?></option>
                            <option value="fixed"><?php echo _l('fixed'); ?></option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <?php echo render_input('discount_value','discount_value','0','number',['min'=>'0','step'=>'0.0001']); ?>
                    </div>
                    <div class="col-md-6">
                        <input type="checkbox" name="active" id="active" checked>
                        <label for="active"><?php echo _l('active'); ?></label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
    </div>
        </div>

    </div>
</div>
<?php init_tail(); ?>
<script>
function new_discount(){
    "use strict";

    console.log('Opening new discount modal'); 
    $('#discount_modal').modal('show');

    $('.edit-title').addClass('hide');
    $('.add-title').removeClass('hide');

    $('#discount_form input[name="id"]').remove();
    $('#sheet_type_id').val('');
    $('#discount_form input[name="min_qty"]').val('0');
    $('#discount_form input[name="max_qty"]').val('');
    $('#discount_type').val('percent');
    $('#discount_form input[name="discount_value"]').val('0');
    $('#active').prop('checked', true);
}

function edit_discount(invoker, id){
    "use strict";

    $('#discount_modal').modal('show');

    $('.edit-title').removeClass('hide');
    $('.add-title').addClass('hide');

    if ($('#discount_form input[name="id"]').length === 0) {
        $('#discount_form').append('<input type="hidden" name="id" value="'+id+'">');
    } else {
        $('#discount_form input[name="id"]').val(id);
    }

    $('#sheet_type_id').val($(invoker).data('sheet_type_id'));
    $('#discount_form input[name="min_qty"]').val(parseInt($(invoker).data('min_qty')));
    $('#discount_form input[name="max_qty"]').val(parseInt($(invoker).data('max_qty')));
    $('#discount_type').val($(invoker).data('discount_type'));
    $('#discount_form input[name="discount_value"]').val(parseFloat($(invoker).data('discount_value')).toFixed(2));

    if($(invoker).data('active') == 1){
        $('#active').prop("checked", true);
    } else {
        $('#active').prop("checked", false);
    }
}

// On form submit, validate
$('#discount_form').submit(function(e){
    "use strict";

    let minQty = parseInt($('#discount_form input[name="min_qty"]').val());
    let maxQty = $('#discount_form input[name="max_qty"]').val() ? parseInt($('#discount_form input[name="max_qty"]').val()) : null;
    let discountType = $('#discount_type').val();
    let discountValue = parseFloat($('#discount_form input[name="discount_value"]').val()).toFixed(2);

    // enforce integers
    $('#discount_form input[name="min_qty"]').val(minQty);
    if(maxQty !== null) $('#discount_form input[name="max_qty"]').val(maxQty);

    // enforce discount value < 100 for percent
    if(discountType === 'percent' && discountValue > 100){
        alert('Percentage discount cannot exceed 100');
        e.preventDefault();
        return false;
    }

    $('#discount_form input[name="discount_value"]').val(discountValue); // round to 2 decimals
});


</script>
