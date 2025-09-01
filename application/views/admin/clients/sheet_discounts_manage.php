<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#customer_sheet_discount_modal">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_customer_group'); ?>
                    </a>
                </div>

                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php render_datatable([
    _l('customer_group_name'),
       _l('sheet_name'),
    _l('discount'),
    _l('margin'),
 _l('override'),
    _l('options'),
], 'client-sheet-discount'); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/clients/client_sheet_discount'); ?>
<?php init_tail(); ?>
<script>
$(function() {
    initDataTable('.table-client-sheet-discount', window.location.href, [5], [5]);
});
</script>
</body>

</html>