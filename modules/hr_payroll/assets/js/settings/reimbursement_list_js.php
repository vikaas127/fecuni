<script>
"use strict";

$(function () {
  appValidateForm($('#reimbursement_form'), {
    'reimbursement_type': 'required',
    'name_in_payslip': 'required',
    'unclaimed_handling': 'required',
    'max_monthly_amount': 'required'
  });
});

// ADD NEW
function new_reimbursement_form() {
  $('#reimbursement_form')[0].reset();
  $('#reimbursement_id').val('');

  $('#reimbursement_form :input').prop('disabled', false);
  $('#reimbursement_form .modal-footer button[type=submit]').show();

  $('#is_fbp_component').prop('checked', false);
  $('#restrict_fbp_override').prop('checked', false);
  $('#is_active').prop('checked', true);

  $('#reimbursement_type').val('').selectpicker('refresh');
  $('#unclaimed_handling').val('').selectpicker('refresh');

  $('.edit-title, .view-title').addClass('hide');
  $('.add-title').removeClass('hide');

  $('#add_reimbursement_modal').modal('show');
}

// EDIT
function edit_reimbursement_form(el, id) {
  const $el = $(el);
  $('#reimbursement_id').val(id);

  $('#reimbursement_type').val($el.data('reimbursement_type')).prop('disabled', false).selectpicker('refresh');
  $('#name_in_payslip').val($el.data('name_in_payslip')).prop('disabled', false);
  $('#max_monthly_amount').val($el.data('max_monthly_amount')).prop('disabled', false);
  $('#unclaimed_handling').val($el.data('unclaimed_handling')).prop('disabled', false).selectpicker('refresh');

  $('#is_fbp_component').prop('checked', $el.data('is_fbp_component') == 1).prop('disabled', false);
  $('#restrict_fbp_override').prop('checked', $el.data('restrict_fbp_override') == 1).prop('disabled', false);
  $('#is_active').prop('checked', $el.data('is_active') == 1).prop('disabled', false);

  $('#reimbursement_form .modal-footer button[type=submit]').show();

  $('.add-title, .view-title').addClass('hide');
  $('.edit-title').removeClass('hide');
  $('#add_reimbursement_modal').modal('show');
}

// VIEW
function view_reimbursement_form(el, id) {
  const $el = $(el);
  $('#reimbursement_id').val(id);

  $('#reimbursement_type').val($el.data('reimbursement_type')).prop('disabled', true).selectpicker('refresh');
  $('#name_in_payslip').val($el.data('name_in_payslip')).prop('disabled', true);
  $('#max_monthly_amount').val($el.data('max_monthly_amount')).prop('disabled', true);
  $('#unclaimed_handling').val($el.data('unclaimed_handling')).prop('disabled', true).selectpicker('refresh');

  $('#is_fbp_component').prop('checked', $el.data('is_fbp_component') == 1).prop('disabled', true);
  $('#restrict_fbp_override').prop('checked', $el.data('restrict_fbp_override') == 1).prop('disabled', true);
  $('#is_active').prop('checked', $el.data('is_active') == 1).prop('disabled', true);

  $('#reimbursement_form .modal-footer button[type=submit]').hide();

  $('.add-title, .edit-title').addClass('hide');
  $('.view-title').removeClass('hide');
  $('#add_reimbursement_modal').modal('show');
}
</script>
