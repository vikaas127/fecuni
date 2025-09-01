<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php
            echo form_open($this->uri->uri_string(), ['id' => 'estimate-form', 'class' => '_transaction_form estimate-form']);
            if (isset($estimate)) {
                echo form_hidden('isedit');
            }
            ?>
            <div class="col-md-12">
                <h4
                    class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-flex tw-items-center tw-space-x-2">
                    <span>
                        <?php echo isset($estimate) ? format_estimate_number($estimate) : _l('create_new_estimate'); ?>
                    </span>
                    <?php echo isset($estimate) ? format_estimate_status($estimate->status) : ''; ?>
                </h4>
                <?php $this->load->view('admin/estimates/estimate_template'); ?>
            </div>
            <?php echo form_close(); ?>
            <?php $this->load->view('admin/invoice_items/item'); ?>
        </div>
    </div>
</div>
</div>
<?php init_tail(); ?>
<script>
$(function() {
    validate_estimate_form();
    // Init accountacy currency symbol
    init_currency();
    // Project ajax search
    init_ajax_project_search_by_customer_id();
    // Maybe items ajax search
    init_ajax_search('items', '#item_select.ajax-search', undefined, admin_url + 'items/search');
});
</script>

<script>
    $(document).ready(function() {

    // Apply filter when any filter changes
    $('.filter-item').on('change', function() {
        applyItemFiltersFlat();
    });

    function applyItemFiltersFlat() {
        var filterData = {
            Length: $('#filter_Length').val(),
            Thickness: $('#filter_Thickness').val(),
            color_id: $('#filter_color').val(),
            finish_type_id: $('#filter_finish').val(),
            sheet_type_id: $('#filter_sheet').val(),
            profile_type_id: $('#filter_profile').val(),
            development_length: $('#filter_development_length').val(),


        };

        console.log('Filters applied:', filterData);

        $.ajax({
            url: admin_url + 'estimates/filter_items_flat',
            method: 'POST',
            data: filterData,
            dataType: 'json',
            success: function(items) {
                console.log('Filtered items:', items);

                var $select = $('#item_select');
                $select.empty();
                $select.append('<option value=""></option>');

                if (items.length > 0) {
                    $.each(items, function(i, item) {
                        var $option = $('<option>', {
                            value: item.id,
                            text: '(' + item.rate + ') ' + item.description,
                            'data-subtext': item.long_description ? item.long_description.substring(0,200) + '...' : ''
                        });
                        $select.append($option);
                    });
                } else {
                    console.warn('No items found for selected filters');
                }

                $select.selectpicker('refresh');
            },
            error: function(xhr, status, error) {
                console.error('Error fetching filtered items:', error, xhr.responseText);
            }
        });
    }

    // Load all items initially
    applyItemFiltersFlat();
});

$('select[name="clientid"]').on('change', function() {
    var customer_id = $(this).val();
    if(customer_id){
        $.get(admin_url + 'estimates/get_customer_group/' + customer_id, function(data){
            if(data && data.group_name){
                $('#customer_group').val(data.group_name);
            } else {
                $('#customer_group').val('');
            }
        }, 'json');
    } else {
        $('#customer_group').val('');
    }
});


</script>
</body>

</html>
