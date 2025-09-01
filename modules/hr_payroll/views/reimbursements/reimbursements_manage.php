<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">

						<div class="row mb-5">
							<div class="col-md-12">
								<h4 class="no-margin"><?php echo _l('hrp_salary_reimbursements'); ?></h4>
							</div>
						</div>
						<br>

						<!-- Filters -->
						<div class="row mb-4">
							<div class="col-md-12">
								<div class="row filter_by">
									<div class="col-md-2">
										<?php echo render_input('month_reimbursements','month',date('Y-m'), 'month'); ?>   
									</div>

									<div class="col-md-3">
										<?php echo render_select('department_reimbursements', $departments, ['departmentid', 'name'], 'department', ''); ?>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="role_reimbursements"><?php echo _l('role'); ?></label>
											<select name="role_reimbursements[]" class="form-control selectpicker" multiple id="role_reimbursements" data-actions-box="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true">
												<?php foreach ($roles as $role) { ?>
													<option value="<?php echo $role['roleid']; ?>"><?php echo $role['name']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="staff_reimbursements"><?php echo _l('staff'); ?></label>
											<select name="staff_reimbursements[]" class="form-control selectpicker" multiple id="staff_reimbursements" data-actions-box="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true">
												<?php foreach ($staffs as $staff) { ?>
													<option value="<?php echo $staff['staffid']; ?>"><?php echo $staff['firstname'].' '.$staff['lastname']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>

						<hr class="hr-color">

						<?php echo form_open(admin_url('hr_payroll/add_manage_reimbursements'), ['id' => 'add_manage_reimbursements']); ?>

						<div class="col-md-12">
							<small><?php echo _l('handsontable_scroll_horizontally'); ?></small>
						</div>

						<div id="total_reimbursement_history" class="col-md-12">
							<div class="row">  
								<div id="hrp_reimbursements_value" class="hot handsontable htColumnHeaders"></div>

								<!-- Hidden fields for data submission -->
								<?php echo form_hidden('hrp_reimbursements_value'); ?>
								<?php echo form_hidden('month', date('m-Y')); ?>
								<?php echo form_hidden('reimbursements_fill_month'); ?>
								<?php echo form_hidden('department_reimbursements_filter'); ?>
								<?php echo form_hidden('staff_reimbursements_filter'); ?>
								<?php echo form_hidden('role_reimbursements_filter'); ?>
								<?php echo form_hidden('hrp_reimbursements_rel_type'); ?>
							</div>
						</div>

						<div class="col-md-12">
							<div class="modal-footer">
								<?php if (has_permission('hrp_reimbursement', '', 'create') || has_permission('hrp_reimbursement', '', 'edit')) { ?>
									<button type="button" class="btn btn-info pull-right save_manage_reimbursements mleft5">
										<?php echo new_html_entity_decode($button_name); ?>
									</button>

									<a href="<?php echo admin_url('hr_payroll/import_xlsx_reimbursements'); ?>" class="hide btn mright5 btn-default pull-right">
										<?php echo _l('hrp_import_excel'); ?>
									</a>
								<?php } ?>
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
<?php require 'modules/hr_payroll/assets/js/reimbursements/reimbursements_manage_js.php'; ?>

</body>
</html>
