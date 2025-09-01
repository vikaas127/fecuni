<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="customer_sheet_discount_modal" tabindex="-1" role="dialog" aria-labelledby="sheetDiscountModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="sheetDiscountModalLabel">
                    <span class="edit-title"><?php echo _l('customer_sheet_discount_edit_heading'); ?></span>
                    <span class="add-title"><?php echo _l('customer_sheet_discount_add_heading'); ?></span>
                </h4>
            </div>
            <?php echo form_open('admin/clients/client_sheet_discount', ['id' => 'client-sheet-discount-form']); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo render_select('group_id', $groups, ['id', 'name'], 'customer_group'); ?>
                    </div>
                  <div class="col-md-6">
    <?php echo render_select('sheet_id', $sheets, ['sheet_type_id', 'sheet_name'], 'sheet'); ?>
</div>

                    <div class="col-md-6">
                        <?php echo render_input('margin', 'default_margin', '', 'number', ['step'=>'0.01', 'min'=>'0', 'max'=>'100']); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo render_input('discount', 'default_discount', '', 'number', ['step'=>'0.01', 'min'=>'0', 'max'=>'100']); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo render_select('override', [
                            ['id'=>0,'name'=>_l('no')],
                            ['id'=>1,'name'=>_l('yes')],
                        ], ['id','name'], 'override_allowed'); ?>
                    </div>

                    <?php echo form_hidden('id'); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button group="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button group="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
    appValidateForm($('#client-sheet-discount-form'), {
        group_id: 'required',
        sheet_id: 'required',
        margin: {  number: true, min: 0, max: 100 },
        discount: {  number: true, min: 0, max: 100 },
    }, manage_customer_sheet_discount);

    // On modal open
// On modal open
$('#customer_sheet_discount_modal').on('show.bs.modal', function(e) {
    var invoker = $(e.relatedTarget);
    var id = $(invoker).data('id');

    var form = $('#client-sheet-discount-form')[0];
    form.reset();

    $('#customer_sheet_discount_modal .add-title').removeClass('hide');
    $('#customer_sheet_discount_modal .edit-title').addClass('hide');

    if (typeof id !== 'undefined') {
        $('#customer_sheet_discount_modal input[name="id"]').val(id);
        $('#customer_sheet_discount_modal .add-title').addClass('hide');
        $('#customer_sheet_discount_modal .edit-title').removeClass('hide');

        var row = $(invoker).closest('tr');

        // Group info
        var groupId = row.find('td').eq(0).find('span').data('id');
        var groupName = row.find('td').eq(0).find('span').data('name');
        console.log('Editing Group:', groupId, groupName);
        $('#customer_sheet_discount_modal select[name="group_id"]').val(groupId).change();

        // Sheet info
        var sheetId = row.find('td').eq(1).find('span').data('id');
        var sheetName = row.find('td').eq(1).find('span').data('name');
        console.log('Editing Sheet:', sheetId, sheetName);
        $('#customer_sheet_discount_modal select[name="sheet_id"]').val(sheetId).change();

        // Discount, Margin, Override
        var discount = row.find('td').eq(2).text().replace('%','').trim();
        var margin = row.find('td').eq(3).text().replace('%','').trim();
        var override = row.find('td').eq(4).find('span').data('val');
        console.log('Discount:', discount, 'Margin:', margin, 'Override:', override);

        $('#customer_sheet_discount_modal input[name="discount"]').val(discount);
        $('#customer_sheet_discount_modal input[name="margin"]').val(margin);
        $('#customer_sheet_discount_modal select[name="override"]').val(override).change();

        if ($('.selectpicker').length) {
            $('#customer_sheet_discount_modal select').selectpicker('refresh');
        }
    }
});


// When group changes
$('#customer_sheet_discount_modal select[name="group_id"]').on('change', function() {
    var group_id = $(this).val(); // selected group
    var current_id = $('#customer_sheet_discount_modal input[name="id"]').val(); // current record id
    var current_sheet = $('#customer_sheet_discount_modal select[name="sheet_id"]').val(); // currently selected sheet in edit

    if (group_id) {
        // Fetch sheets already used by this group, except the current record
        $.get(admin_url + 'clients/get_used_sheets_by_group/' + group_id + '/' + current_id, function(res) {
            var usedSheets = JSON.parse(res);

            // Enable all options first
            $('#customer_sheet_discount_modal select[name="sheet_id"] option').prop('disabled', false);

            // Disable sheets already used by this group (except current)
            usedSheets.forEach(function(sheetId) {
                if (sheetId != current_sheet) {
                    $('#customer_sheet_discount_modal select[name="sheet_id"] option[value="' + sheetId + '"]').prop('disabled', true);
                }
            });

            // Refresh selectpicker to reflect changes
            if ($('.selectpicker').length) {
                $('#customer_sheet_discount_modal select[name="sheet_id"]').selectpicker('refresh');
            }
        });
    } else {
        // If no group selected, enable all sheets
        $('#customer_sheet_discount_modal select[name="sheet_id"] option').prop('disabled', false);
        if ($('.selectpicker').length) {
            $('#customer_sheet_discount_modal select[name="sheet_id"]').selectpicker('refresh');
        }
    }
});
  
});

function manage_customer_sheet_discount(form) {
    var data = $(form).serialize();
    var url = form.attr('action');

    $.post(url, data).done(function(response) {
        response = JSON.parse(response);

        if (response.success === true) {
            if ($.fn.DataTable.isDataTable('.table-client-sheet-discount')) {
                $('.table-client-sheet-discount').DataTable().ajax.reload();
            }
            alert_float('success', response.message);
        }

        $('#customer_sheet_discount_modal').modal('hide');
    });

    return false;
}

</script>