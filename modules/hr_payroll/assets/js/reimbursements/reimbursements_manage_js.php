<script>
	var purchase;

	<?php if (isset($body_value)) { ?>
		var dataObject = <?php echo new_html_entity_decode($body_value); ?>;
	<?php } ?>

	var hotElement1 = document.querySelector('#hrp_reimbursements_value');
	purchase = new Handsontable(hotElement1, {
		contextMenu: true,
		manualRowMove: true,
		manualColumnMove: true,
		stretchH: 'all',
		autoWrapRow: true,
		rowHeights: 40,
		defaultRowHeight: 10,
		width: '100%',
		height: 600,
		licenseKey: 'non-commercial-and-evaluation',
		rowHeaders: true,
		dropdownMenu: true,
		hiddenColumns: {
			columns: [0, 1, 2],
			indicators: false
		},
		multiColumnSorting: {
			indicator: true
		},
		fixedColumnsLeft: 5,
		filters: true,
		allowInsertRow: false,
		allowRemoveRow: false,
		columnHeaderHeight: 40,
		rowHeaderWidth: [44],
		columns: <?php echo new_html_entity_decode($columns); ?>,
		colHeaders: <?php echo new_html_entity_decode($col_header); ?>,
		data: dataObject
	});

	var purchase_value = purchase;

	function reimbursements_filter() {
		'use strict';

		var data = {};
		data.month = $("#month_reimbursements").val();
		data.staff = $('select[name="staff_reimbursements[]"]').val();
		data.department = $('#department_reimbursements').val();
		data.role = $('select[name="role_reimbursements[]"]').val();

		$.post(admin_url + 'hr_payroll/reimbursements_filter', data).done(function(response) {
			response = JSON.parse(response);
			dataObject = response.data_object;
			purchase.updateSettings({ data: dataObject });

			$('input[name="month"]').val(response.month);
			$('.save_manage_reimbursements').html(response.button_name);
		});
	}

	$('.save_manage_reimbursements').on('click', function() {
		'use strict';

		var invalid = $('#hrp_reimbursements_value').find('.htInvalid').html();
		if (invalid) {
			alert_float('danger', "<?php echo _l('data_invalid'); ?>");
		} else {
			$('input[name="hrp_reimbursements_value"]').val(JSON.stringify(purchase_value.getData()));
			$('input[name="reimbursements_fill_month"]').val($("#month_reimbursements").val());
			$('input[name="hrp_reimbursements_rel_type"]').val('update');
			$('#add_manage_reimbursements').submit();
		}
	});

	$('#department_reimbursements').on('change', function() {
		'use strict';
		$('input[name="department_reimbursements_filter"]').val($(this).val());
		reimbursements_filter();
	});

	$('#staff_reimbursements').on('change', function() {
		'use strict';
		$('input[name="staff_reimbursements_filter"]').val($(this).val());
		reimbursements_filter();
	});

	$('#role_reimbursements').on('change', function() {
		'use strict';
		$('input[name="role_reimbursements_filter"]').val($(this).val());
		reimbursements_filter();
	});

	$('#month_reimbursements').on('change', function() {
		'use strict';
		reimbursements_filter();
	});
</script>
