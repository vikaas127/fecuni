
appValidateForm($('#salary_form_manage'), {
    warehouse_code: 'required',
    warehouse_name: 'required',
    
});
function new_salary_form() {
  'use strict';

  $('#salary_form').modal('show');
  $('.edit-title').addClass('hide');
  $('.add-title').removeClass('hide');
  $('#additional_salary_form').html('');

  // Clear values
  $('#form_name').val('');
  $('#salary_val').val('');
  $('#calculation_type').selectpicker('val', '0').change(); // default to Flat
  $('#pay_type').selectpicker('val', 'Fixed');

  // Reset checkboxes to checked
  const checkboxes = [
    'active', 'is_part_of_structure', 'is_tax_deducted_monthly',
    'is_prorated', 'consider_for_epf', 'consider_for_esi', 'show_in_payslip'
  ];
  checkboxes.forEach(id => {
    $('#' + id).prop('checked', true);
  });
}

function edit_salary_form(invoker, id) {
  'use strict';

  $('#additional_salary_form').html('');
  $('#additional_salary_form').append(hidden_input('id', id));

  $('#form_name').val($(invoker).data('name'));
  $('#salary_val').val($(invoker).data('salary'));
  $('#calculation_type').selectpicker('val', $(invoker).data('calculation-type')).change();
  $('#pay_type').selectpicker('val', 'Fixed'); // Adjust if dynamic later

  // Set checkboxes based on data attributes
  const checkboxes = [
    'active', 'is_part_of_structure', 'is_tax_deducted_monthly',
    'is_prorated', 'consider_for_epf', 'consider_for_esi', 'show_in_payslip'
  ];
  checkboxes.forEach(id => {
    const val = $(invoker).data(id.replace(/_/g, '-'));
    $('#' + id).prop('checked', val == 1);
  });

  $('.edit-title').removeClass('hide');
  $('.add-title').addClass('hide');
  $('#salary_form').modal('show');
}

// Dynamically update label based on calculation type
function update_amount_label(type) {
  if (parseInt(type) === 0) {
    $('label[for="percentage"]').text('Amount');
  } else {
    $('label[for="percentage"]').text('Percentage (%)');
  }
}

function toggle_calculation_fields(type) {
  update_amount_label(type); // Only change label; field remains the same
}

$(function () {
  $('#calculation_type').on('change', function () {
    toggle_calculation_fields($(this).val());
  });

  // Set default on page load
  update_amount_label($('#calculation_type').val());
});


function formatNumber(n) {
    'use strict';

  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}

function formatCurrency(input, blur) {
  "use strict";

  var input_val = input.val();
  if (input_val === "") { return; }
  var original_len = input_val.length; 
  var caret_pos = input.prop("selectionStart");
  if (input_val.indexOf(".") >= 0) {
    var decimal_pos = input_val.indexOf(".");
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);
    left_side = formatNumber(left_side);
    right_side = formatNumber(right_side);
    right_side = right_side.substring(0, 2);
    input_val = left_side + "." + right_side;
  } else {

    input_val = formatNumber(input_val);
    input_val = input_val;
  }
  input.val(input_val);
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}