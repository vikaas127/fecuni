<!-- <script>
	var purchase;

	(function($) {
		"use strict";  


	<?php if(isset($insurance_list)){?>
		var dataObject_pu = <?php echo new_html_entity_decode($insurance_list); ?>;
	<?php }else{ ?>
		var dataObject_pu = [];
	<?php } ?>

	//hansometable for purchase
	var row_global;
	var hotElement1 = document.getElementById('insurance_list_hs');

	purchase = new Handsontable(hotElement1, {
		licenseKey: 'non-commercial-and-evaluation',

		contextMenu: true,
		manualRowMove: true,
		manualColumnMove: true,
		stretchH: 'all',
		autoWrapRow: true,
		rowHeights: 30,
		defaultRowHeight: 100,
		minRows: 10,
		maxRows: 40,
		width: '100%',

		rowHeaders: true,
		colHeaders: true,
		autoColumnSize: {
			samplingRatio: 23
		},

		filters: true,
		manualRowResize: true,
		manualColumnResize: true,
		allowInsertRow: true,
		allowRemoveRow: true,
		columnHeaderHeight: 40,

		colWidths:  [40, 100, 50],
		rowHeights: 30,
		rowHeaderWidth: [44],
		minSpareRows: 1,
		hiddenColumns: {
			columns: [4],
			indicators: true
		},
		autoWrapCol: true,
		autoWrapRow: true,
		columns: [
				{
			type: 'text',
			data: 'code',
		},
		{
			type: 'text',
			data: 'description',
		},
		{
			type: 'numeric',
			data: 'rate',
			numericFormat: {
				pattern: '0,00',
			},
		},

		{
			type: 'text',
			data: 'basis',
			renderer: customDropdownRenderer,
			editor: "chosen",
			chosenOptions: {
				data: <?php echo json_encode($basis_value); ?>
			}

		},

		{
			type: 'text',
			data: 'id',
		},
		

		],

		colHeaders: [
		'<?php echo _l('salary_deduction_code'); ?>',
		'<?php echo _l('salary_deduction_name'); ?>',
		'<?php echo _l('salary_deduction_rate'); ?>',
		'<?php echo _l('salary_deduction_basis'); ?>',
		'<?php echo _l('id'); ?>',
		],

		data: dataObject_pu,
	});


	})(jQuery);

	function customDropdownRenderer(instance, td, row, col, prop, value, cellProperties) {
		"use strict";
		var selectedId;
		var optionsList = cellProperties.chosenOptions.data;
		
		if(typeof optionsList === "undefined" || typeof optionsList.length === "undefined" || !optionsList.length) {
			Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
			return td;
		}

		var values = (value + "").split("|");
		value = [];
		for (var index = 0; index < optionsList.length; index++) {

			if (values.indexOf(optionsList[index].id + "") > -1) {
				selectedId = optionsList[index].id;
				value.push(optionsList[index].label);
			}
		}
		value = value.join(", ");

		Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
		return td;
	}

	var purchase_value = purchase;

	$('.add_insurance_list').on('click', function() {
		'use strict';
		
		var valid_contract = $('#insurance_list_hs').find('.htInvalid').html();

		if(valid_contract){
			alert_float('danger', "<?php echo _l('data_must_number') ; ?>");
		}else{

			$('input[name="insurance_list_hs"]').val(JSON.stringify(purchase_value.getData()));   
			$('#add_insurance_list').submit(); 

		}
	});


</script> -->
<script>
"use strict";

$(function () {
  // Form validation
  appValidateForm($('#insurance_form'), {
    'code': 'required',
    'rate': 'required',
    'basis': 'required',
    'benefit_plan_type': 'required'
  });

  // Hide associated_section if NPS is selected
  $('#benefit_plan_type').on('change', function () {
    const selected = $(this).val();
    if (selected === 'NPS') {
      $('#associated_section').val([]).change(); // clear values
      $('#associated_section').closest('.form-group').hide(); // hide the field
    } else {
      $('#associated_section').closest('.form-group').show(); // show the field
    }
  });

  // Trigger change once on load for edit/view/new mode
  $('#benefit_plan_type').trigger('change');
});

// ADD NEW
function new_insurance_form() {
  $('#insurance_form')[0].reset();
  $('#plan_id').val('');
  $('#associated_section').val([]).change();

  $('#include_employer_contribution').prop('checked', false);
  $('#is_superannuation_fund').prop('checked', false);
  $('#calculate_pro_rata').prop('checked', false);
  $('#is_active').prop('checked', true);

  $('#insurance_form :input').prop('disabled', false);
  $('#insurance_form .modal-footer button[type=submit]').show();

  $('.edit-title, .view-title').addClass('hide');
  $('.add-title').removeClass('hide');
  $('#add_insurance_modal').modal('show');

  $('#benefit_plan_type').trigger('change'); // ensure correct visibility
}

// EDIT
function edit_insurance_form(el, id) {
  const $el = $(el);
  $('#plan_id').val(id);
  $('#code').val($el.data('code')).prop('disabled', false);
  $('#description').val($el.data('description')).prop('disabled', false);
  $('#rate').val($el.data('rate')).prop('disabled', false);
  $('#basis').val($el.data('basis')).prop('disabled', false).change();
  $('#benefit_plan_type').val($el.data('benefit-type')).prop('disabled', false).change();

  let section = $el.data('associated-section');
  try {
    section = JSON.parse(section);
  } catch (e) {
    section = [section];
  }
  $('#associated_section').val(section).prop('disabled', false).change();

  $('#include_employer_contribution').prop('checked', $el.data('include-employer-contribution') == 1).prop('disabled', false);
  $('#is_superannuation_fund').prop('checked', $el.data('is-superannuation-fund') == 1).prop('disabled', false);
  $('#calculate_pro_rata').prop('checked', $el.data('calculate-pro-rata') == 1).prop('disabled', false);
  $('#is_active').prop('checked', $el.data('is-active') == 1).prop('disabled', false);

  $('#insurance_form .modal-footer button[type=submit]').show();

  $('.add-title, .view-title').addClass('hide');
  $('.edit-title').removeClass('hide');
  $('#add_insurance_modal').modal('show');

  $('#benefit_plan_type').trigger('change'); // ensure correct visibility
}

// VIEW
function view_insurance_form(el, id) {
  const $el = $(el);
  $('#plan_id').val(id);
  $('#code').val($el.data('code')).prop('disabled', true);
  $('#description').val($el.data('description')).prop('disabled', true);
  $('#rate').val($el.data('rate')).prop('disabled', true);
  $('#basis').val($el.data('basis')).prop('disabled', true).change();
  $('#benefit_plan_type').val($el.data('benefit-type')).prop('disabled', true).change();

  let section = $el.data('associated-section');
  try {
    section = JSON.parse(section);
  } catch (e) {
    section = [section];
  }
  $('#associated_section').val(section).prop('disabled', true).change();

  $('#include_employer_contribution').prop('checked', $el.data('include-employer-contribution') == 1).prop('disabled', true);
  $('#is_superannuation_fund').prop('checked', $el.data('is-superannuation-fund') == 1).prop('disabled', true);
  $('#calculate_pro_rata').prop('checked', $el.data('calculate-pro-rata') == 1).prop('disabled', true);
  $('#is_active').prop('checked', $el.data('is-active') == 1).prop('disabled', true);

  $('#insurance_form .modal-footer button[type=submit]').hide();

  $('.add-title, .edit-title').addClass('hide');
  $('.view-title').removeClass('hide');
  $('#add_insurance_modal').modal('show');

  $('#benefit_plan_type').trigger('change'); // ensure correct visibility
}
</script>
