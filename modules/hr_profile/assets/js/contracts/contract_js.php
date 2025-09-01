<script>
	var purchase;

	$(function() {
		'use strict';
		
		appValidateForm($('.staff-contract-form'), {
			name_contract: 'required',
			staff: 'required',
			start_valid: 'required',
			contract_status: 'required',
			contract_code: {
				required: true,
				remote: {
					url: site_url + "admin/hr_profile/contract_code_exists",
					type: 'post',
					data: {
						contract_code: function() {
							return $('input[name="contract_code"]').val();
						},
						contractid: function() {
							return $('input[name="contractid"]').val();
						}
					}
				}
			}
		});
	});
	

	function manage_contract_type(form) {
		'use strict';

		var data = $(form).serialize();
		var url = form.action;
		$.post(url, data).done(function(response){
			response = JSON.parse(response);
			if(response.success == true){
				alert_float('success',response.message);
				if($('body').hasClass('contract') && typeof(response.id) != 'undefined') {
					var ctype = $('#contract_forms');
					ctype.find('option:first').after('<option value="'+response.id+'">'+response.name+'</option>');
					ctype.selectpicker('val',response.id);
					ctype.selectpicker('refresh');
				}
			}
			$('#form').modal('hide')
		});
		return false;
	}


		// + button for adding more attachments
		var addMoreAttachmentsInputKey = 1;
		//button for adding more attachment in project
		$("body").on('click', '.add_more_attachments_file', function() {
			'use strict';

			if ($(this).hasClass('disabled')) {
				return false;
			}

			var total_attachments = $('.attachments input[name*="file"]').length;
			if ($(this).data('max') && total_attachments >= $(this).data('max')) {
				return false;
			}

			var newattachment = $('.attachments').find('.attachment').eq(0).clone().appendTo('.attachments');
			newattachment.find('input').removeAttr('aria-describedby aria-invalid');
			newattachment.find('input').attr('name', 'file[' + addMoreAttachmentsInputKey + ']').val('');
			newattachment.find($.fn.appFormValidator.internal_options.error_element + '[id*="error"]').remove();
			newattachment.find('.' + $.fn.appFormValidator.internal_options.field_wrapper_class).removeClass($.fn.appFormValidator.internal_options.field_wrapper_error_class);
			newattachment.find('i').removeClass('fa-plus').addClass('fa-minus');
			newattachment.find('button').removeClass('add_more_attachments_file').addClass('remove_attachment_file').removeClass('btn-success').addClass('btn-danger');
			addMoreAttachmentsInputKey++;
		});

		// Remove attachment
		$("body").on('click', '.remove_attachment_file', function() {
			'use strict';

			$(this).parents('.attachment').remove();
		}); 

		//disabled input jobposition
		$( "#job_position" ).prop( "disabled", true );
		$("#staff_delegate").change(function(){
			'use strict';

			var formData = new FormData();
			formData.append("csrf_token_name", $('input[name="csrf_token_name"]').val());
			formData.append("id", $(this).children("option:selected").val());
			$.ajax({ 
				url: admin_url + 'hr_profile/get_staff_role', 
				method: 'post', 
				data: formData, 
				contentType: false, 
				processData: false
			}).done(function(response) {
				response = JSON.parse(response);
				if(response.name != null ){
					$('#job_position').val(response.name);
				}
			});
			return false;

		});



		//function delete contract attachment file 
		function delete_contract_attachment(wrapper, id) {
			'use strict';

			if (confirm_delete()) {
				$.get(admin_url + 'hr_profile/delete_hrm_contract_attachment_file/' + id, function (response) {
					if (response.success == true) {
						$(wrapper).parents('.contract-attachment-wrapper').remove();

						var totalAttachmentsIndicator = $('.attachments-indicator');
						var totalAttachments = totalAttachmentsIndicator.text().trim();
						if(totalAttachments == 1) {
							totalAttachmentsIndicator.remove();
						} else {
							totalAttachmentsIndicator.text(totalAttachments-1);
						}
					} else {
						alert_float('danger', response.message);
					}
				}, 'json');
			}
			return false;
		}

	//contract preview file
	function preview_file_staff(invoker){
		'use strict';

		var id = $(invoker).attr('id');
		var rel_id = $(invoker).attr('rel_id');
		view_hrmstaff_file(id, rel_id);
	}

	 //function view hrm_file
	 function view_hrmstaff_file(id, rel_id) {   
	 	'use strict';

	 	$('#contract_file_data').empty();
	 	$("#contract_file_data").load(admin_url + 'hr_profile/hrm_file_contract/' + id + '/' + rel_id, function(response, status, xhr) {
	 		if (status == "error") {
	 			alert_float('danger', xhr.statusText);
	 		}
	 	});
	 }

function percentageRenderer(instance, td, row, col, prop, value, cellProperties) {
  const rowData = instance.getSourceDataAtRow(row);
  const type = parseInt(rowData.calculation_type);

  let display = '';

  if (type === 0) {
    display = 'Fixed Amount';
  } else if (type === 1) {
    display = isNaN(value) ? '' : `${value} % of Basic`;
  } else if (type === 2) {
    display = isNaN(value) ? '' : `${value} % of CTC`;
  }

  td.innerHTML = display;
  td.className = 'htMiddle htLeft';
}



	 (function($) {
	 	"use strict";  


	 	<?php if(!isset($contracts)){ ?>
	 		var warehouses ={};
	//hansometable for purchase
	var row_global;
	var dataObject_pu = <?php echo json_encode($default_structure_rows); ?>;
	var
	hotElement1 = document.getElementById('staff_contract_hs');

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
		allowRemoveRow: false,
		columnHeaderHeight: 40,

		colWidths: [40, 120,50,50, 100,100],
		rowHeights: 30,
		rowHeaderWidth: [44],
		minSpareRows: 1,
		hiddenColumns: {
			columns: [0],
			indicators: true
		},

		columns: [
		{
			type: 'text',
			data: 'type',
			renderer: customDropdownRenderer,
			editor: "chosen",
			chosenOptions: {
				data: <?php echo json_encode($types); ?>
			},
			readOnly: true
		},
		{
			type: 'text',
			data: 'rel_type',
			renderer: customDropdownRenderer,
			editor: "chosen",
			chosenOptions: {
				data: <?php echo json_encode($salary_allowance_type); ?>
			}

		},
		{
  data: 'calculation_type',
  type: 'text',
  readOnly: true
}


,{
  data: 'percentage_value',
  type: 'numeric',
  renderer: percentageRenderer,
  numericFormat: {
    pattern: '0.00'
  }
}
,

		{
			type: 'numeric',
			data: 'rel_value',
			numericFormat: {
				pattern: '0,00',
			},
		},
			{
	type: 'numeric',
	data: 'annual_value',
	numericFormat: {
		pattern: '0,00',
	},
	readOnly: true
}
,

		{
			type: 'date',
			data: 'since_date',
			dateFormat: 'YYYY-MM-DD',
			correctFormat: true,
			defaultDate: "<?php echo _d(date('Y-m-d')) ?>"
		},

		{
			type: 'text',
			data: 'contract_note',
		},

		],

			colHeaders: [
		'<?php echo _l('hr_hr_contract_type'); ?>',
		'<?php echo _l('hr_hr_contract_rel_type'); ?>',
		  '<?php echo _l('hr_calculation_type'); ?>',
		  		  '<?php echo _l('hr_percentage_type'); ?>',

		'<?php echo _l('hr_hr_contract_rel_value'); ?>',
				'<?php echo _l('hr_hr_contract_annual_value'); ?>',

		'<?php echo _l('hr_start_month'); ?>',
		'<?php echo _l('note'); ?>',

		],

		data: dataObject_pu,
		cells: function(row, col, prop) {
  const cellProperties = {};
  const rowData = this.instance.getSourceDataAtRow(row);

  // Set editable only if calculation_type == 1
 if (prop === 'percentage_value') {
  const type = parseInt(rowData.calculation_type);
  cellProperties.readOnly = !(type === 1 || type === 2);
}
if (prop === 'rel_value') {
  const type = parseInt(rowData.calculation_type);
  cellProperties.readOnly = !(type === 0);  // Only editable if type is 0
}


  // Prevent duplicate rel_type selection
  if (prop === 'rel_type') {
    const selectedRelTypes = new Set();
    const tableData = this.instance.getData();

    for (let r = 0; r < tableData.length; r++) {
      if (r !== row && tableData[r][1]) {
        selectedRelTypes.add(tableData[r][1]);
      }
    }

    const availableOptions = <?php echo json_encode($salary_allowance_type); ?>.filter(opt => {
      return !selectedRelTypes.has(opt.id);
    });

    cellProperties.renderer = customDropdownRenderer;
    cellProperties.editor = 'chosen';
    cellProperties.chosenOptions = {
      data: availableOptions
    };
  }

  return cellProperties;
},


	});

<?php }else{ ?>


<?php if (isset($contract_details) && !empty($contract_details)) { ?>
	var dataObject_pu = <?php echo new_html_entity_decode($contract_details); ?>;
<?php } else { ?>
	var dataObject_pu = <?php echo json_encode($default_structure_rows); ?>;
<?php } ?>

console.log(" DEBUG dataObject_pu:", dataObject_pu);



	var warehouses ={};
	//hansometable for purchase
	var row_global;
	var hotElement1 = document.getElementById('staff_contract_hs');

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
		allowRemoveRow: false,
		columnHeaderHeight: 40,

		colWidths: [40, 120,50,50, 100,100],
		rowHeights: 30,
		rowHeaderWidth: [44],
		minSpareRows: 1,
		hiddenColumns: {
			columns: [0,8,9],
			indicators: true
		},

		columns: [
		{
			type: 'text',
			data: 'type',
			renderer: customDropdownRenderer,
			editor: "chosen",
			chosenOptions: {
				data: <?php echo json_encode($types); ?>
			},
			readOnly: true
		},
		{
			type: 'text',
			data: 'rel_type',
			renderer: customDropdownRenderer,
			editor: "chosen",
			chosenOptions: {
				data: <?php echo json_encode($salary_allowance_type); ?>
			}

		},

	{
  data: 'calculation_type',
  type: 'text',
  readOnly: true
}


,		{
  data: 'percentage_value',
  type: 'numeric',
  renderer: percentageRenderer,
  numericFormat: {
    pattern: '0.00'
  }
}


,

		{
			type: 'numeric',
			data: 'rel_value',
			numericFormat: {
				pattern: '0,00',
			},
		},

		{
	type: 'numeric',
	data: 'annual_value',
	numericFormat: {
		pattern: '0,00',
	},
	readOnly: true
}
,

		{
			type: 'date',
			data: 'since_date',
			dateFormat: 'YYYY-MM-DD',
			correctFormat: true,
			defaultDate: "<?php echo _d(date('Y-m-d')) ?>"
		},

		{
			type: 'text',
			data: 'contract_note',
		},
		{
			type: 'text',
			data: 'contract_detail_id',
		},
		{
			type: 'text',
			data: 'staff_contract_id',
		},
		

		],

		colHeaders: [
		'<?php echo _l('hr_hr_contract_type'); ?>',
		'<?php echo _l('hr_hr_contract_rel_type'); ?>',
		'<?php echo _l('hr_calculation_type'); ?>',
		'<?php echo _l('hr_percentage_type'); ?>',

		'<?php echo _l('hr_hr_contract_rel_value'); ?>',
		'<?php echo _l('hr_hr_contract_annual_value'); ?>',

		'<?php echo _l('hr_start_month'); ?>',
		'<?php echo _l('note'); ?>',

		],

		data: dataObject_pu,
cells: function(row, col, prop) {
  const cellProperties = {};
  const rowData = this.instance.getSourceDataAtRow(row);

  // Set editable only if calculation_type == 1
 if (prop === 'percentage_value') {
  const type = parseInt(rowData.calculation_type);
  cellProperties.readOnly = !(type === 1 || type === 2);
}
if (prop === 'rel_value') {
  const type = parseInt(rowData.calculation_type);
  cellProperties.readOnly = !(type === 0);  // Only editable if type is 0
}


  // Prevent duplicate rel_type selection
  if (prop === 'rel_type') {
    const selectedRelTypes = new Set();
    const tableData = this.instance.getData();

    for (let r = 0; r < tableData.length; r++) {
      if (r !== row && tableData[r][1]) {
        selectedRelTypes.add(tableData[r][1]);
      }
    }

    const availableOptions = <?php echo json_encode($salary_allowance_type); ?>.filter(opt => {
      return !selectedRelTypes.has(opt.id);
    });

    cellProperties.renderer = customDropdownRenderer;
    cellProperties.editor = 'chosen';
    cellProperties.chosenOptions = {
      data: availableOptions
    };
  }

  return cellProperties;
},


	});

<?php } ?>


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
purchase.addHook('afterChange', function (changes, source) {
  if (!changes || source !== 'edit') return;

  changes.forEach(([row, prop, oldValue, newValue]) => {
    if (prop === 'rel_type' && newValue !== '' && newValue !== oldValue) {
      console.log('[DEBUG] rel_type changed to:', newValue);

      $.get(site_url + 'admin/hr_profile/get_salary_allowance_value/' + newValue)
        .done(function (res) {
          console.log('[DEBUG] Response from PHP:', res);
          const response = JSON.parse(res);

          // Update cells based on PHP-calculated values
          purchase.setDataAtCell(row, 0, response.type);
          purchase.setDataAtCell(row, 2, response.calculation_type);
          purchase.setDataAtCell(row, 3, response.percentage_value);
          purchase.setDataAtCell(row, 4, response.rel_value);
          purchase.setDataAtCell(row, 5, response.annual_value);
          purchase.setDataAtCell(row, 6, response.effective_date);

          updateTotals();
        })
        .fail(function (xhr) {
          console.error('[ERROR] Fetch failed:', xhr.responseText);
        });
    }

    if (prop === 'percentage_value') {
      $('#ctc').trigger('input');
    }

    if (prop === 'rel_value') {
      const rowData = purchase.getSourceDataAtRow(row);
      if (parseInt(rowData.calculation_type) === 0) {
        const rel = parseFloat(newValue);
        if (!isNaN(rel)) {
          const annual = rel * 12;
          purchase.setDataAtRowProp(row, 'annual_value', parseFloat(annual.toFixed(2)));
        }
      }
    }
  });
});








$('.add_goods_receipt').on('click', function () {
  'use strict';

  var valid_contract = $('#staff_contract_hs').find('.htInvalid').html();

  if (valid_contract) {
    alert_float('danger', "<?php echo _l('data_must_number'); ?>");
    return;
  }

  const ctc = parseFloat($('#ctc').val());
  const totalAnnual = parseFloat($('#total_annual_value').text().replace(/,/g, ''));

  if (isNaN(ctc)) {
    alert_float('danger', 'Please enter a valid CTC value.');
    return;
  }

  const diff = (totalAnnual - ctc).toFixed(2);
  const diffAbs = Math.abs(diff);
  const diffMonthly = (diffAbs / 12).toFixed(2);

 if (diffAbs > 50) {
    if (diff > 0) {
      alert_float('danger',
        `Total Annual Value exceeds CTC by ₹${diffAbs}.\nYou need to reduce ₹${diffMonthly} per month.`);
    } else {
      alert_float('danger',
        `Total Annual Value is ₹${diffAbs} less than CTC.\nYou need to add ₹${diffMonthly} per month.`);
    }
    return;
  }

  // ✅ Submit when total equals CTC
  $('input[name="staff_contract_hs"]').val(JSON.stringify(purchase.getData()));
  $('.staff-contract-form').submit();
});


// $('#ctc').on('input', function () {
//   const ctc = parseFloat($(this).val());

//   if (!isNaN(ctc)) {
//     const data = purchase.getSourceData();
//     let basicValue = null;

//     // Step 1: Find Basic (form_id = 4 → rel_type = "st_4")
//     data.forEach((row) => {
//       if (row.rel_type === 'st_4') {
//         const calcType = parseInt(row.calculation_type);
//         const percent = parseFloat(row.percentage_value);  // ✅ updated

//         if (!isNaN(percent)) {
//           if (calcType === 0) {
//             basicValue = percent;
//           } else if (calcType === 2) {
//             basicValue = (percent * ctc) / (12 * 100);
//           }
//         }
//       }
//     });

//     // Step 2: Update values
//     data.forEach((row, rowIndex) => {
// 		const calcType = parseInt(row.calculation_type);
// 		const percent = parseFloat(row.percentage_value);  

// 		if (!isNaN(percent)) {
// 			if (calcType === 2) {
// 			const monthlyValue = (percent * ctc) / (12 * 100);
// 			const annualValue = (percent * ctc) / 100;

// 			purchase.setDataAtRowProp(rowIndex, 'rel_value', parseFloat(monthlyValue.toFixed(2)));
// 			purchase.setDataAtRowProp(rowIndex, 'annual_value', parseFloat(annualValue.toFixed(2)));
// 			}

// 			if (calcType === 1 && basicValue !== null) {
// 			const monthlyValue = (percent * basicValue) / 100;
// 			const annualValue = monthlyValue * 12;

// 			purchase.setDataAtRowProp(rowIndex, 'rel_value', parseFloat(monthlyValue.toFixed(2)));
// 			purchase.setDataAtRowProp(rowIndex, 'annual_value', parseFloat(annualValue.toFixed(2)));
// 			}
// 	}
// 	});

// }
// updateTotals(); 
// });

// 🔁 Trigger calculation on blur (when input loses focus)
$('#ctc').on('blur', function () {
  const ctc = parseFloat($(this).val());

  if (isNaN(ctc)) {
    alert_float('danger', 'Please enter a valid CTC value.');
    return;
  }

  const data = purchase.getSourceData();
  let basicValue = null;

  // Step 1: Find Basic (rel_type = "st_4")
  data.forEach((row) => {
    if (row.rel_type === 'st_4') {
      const calcType = parseInt(row.calculation_type);
      const percent = parseFloat(row.percentage_value);

      if (!isNaN(percent)) {
        if (calcType === 0) {
          basicValue = percent;
        } else if (calcType === 2) {
          basicValue = (percent * ctc) / (12 * 100);
        }
      }
    }
  });

  // Step 2: Update values
  data.forEach((row, rowIndex) => {
    const calcType = parseInt(row.calculation_type);
    const percent = parseFloat(row.percentage_value);

    if (!isNaN(percent)) {
      if (calcType === 2) {
        const monthlyValue = (percent * ctc) / (12 * 100);
        const annualValue = (percent * ctc) / 100;

        purchase.setDataAtRowProp(rowIndex, 'rel_value', parseFloat(monthlyValue.toFixed(2)));
        purchase.setDataAtRowProp(rowIndex, 'annual_value', parseFloat(annualValue.toFixed(2)));
      }

      if (calcType === 1 && basicValue !== null) {
        const monthlyValue = (percent * basicValue) / 100;
        const annualValue = monthlyValue * 12;

        purchase.setDataAtRowProp(rowIndex, 'rel_value', parseFloat(monthlyValue.toFixed(2)));
        purchase.setDataAtRowProp(rowIndex, 'annual_value', parseFloat(annualValue.toFixed(2)));
      }
    }
  });

  updateTotals();
});


// ✅ Optional: Allow Enter key to also trigger blur (same as done typing)
$('#ctc').on('keypress', function (e) {
  if (e.which === 13) {
    $(this).blur();
  }
});


// ✅ Optional: Debounce typing (optional if you prefer delay calculation while typing)
let ctcDebounceTimer;
$('#ctc').on('input', function () {
  clearTimeout(ctcDebounceTimer);
  ctcDebounceTimer = setTimeout(() => {
    $(this).trigger('blur');
  }, 600); // 600ms delay after last keystroke
});



purchase.addHook('afterChange', function (changes, source) {
  if (!changes || source !== 'edit') return;

  changes.forEach(([row, prop, oldValue, newValue]) => {
    if (prop === 'rel_value') {
      const rowData = purchase.getSourceDataAtRow(row);
      if (parseInt(rowData.calculation_type) === 0) {
        const rel = parseFloat(newValue);
        if (!isNaN(rel)) {
          const annual = rel * 12;
          purchase.setDataAtRowProp(row, 'annual_value', parseFloat(annual.toFixed(2)));
        }
      }
    }

    if (prop === 'percentage_value') {
      $('#ctc').trigger('input');
    }
  });
});


// Recalculate totals when a new row is added
purchase.addHook('afterCreateRow', function (index, amount) {
  updateTotals();
});

// Recalculate totals when a row is removed
purchase.addHook('afterRemoveRow', function (index, amount) {
  updateTotals();
});


function updateTotals() {
  const data = purchase.getSourceData();  // <- updated
  let totalRel = 0;
  let totalAnnual = 0;

  data.forEach(row => {
    const rel = parseFloat(row.rel_value);
    const annual = parseFloat(row.annual_value);

    if (!isNaN(rel)) totalRel += rel;
    if (!isNaN(annual)) totalAnnual += annual;
  });

  $('#total_rel_value').text(totalRel.toLocaleString('en-IN', { minimumFractionDigits: 2 }));
  $('#total_annual_value').text(totalAnnual.toLocaleString('en-IN', { minimumFractionDigits: 2 }));
}

purchase.addHook('afterChange', function () {
  updateTotals();
});

$(document).ready(function () {
  updateTotals(); // ← recalculate on load
});




</script>