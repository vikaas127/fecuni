<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div>
	<div class="_buttons">
		<?php if(has_permission('hrm_setting', '', 'create') || is_admin() ){ ?>
			<a href="#" onclick="new_salary_form(); return false;" class="btn btn-primary pull-left display-block">
				<?php echo _l('hr_hr_add'); ?>
			</a>
		<?php } ?>
	</div>
	<div class="clearfix"></div>
	<br>

	<table class="table dt-table">
		<thead>
			<th><?php echo _l('hr_salary_form_name'); ?></th>
			    <th><?php echo _l('calculation_type'); ?></th>

			<th><?php echo _l('amount'); ?></th>

			<th class="hide"><?php echo _l('taxable'); ?></th>
			<th><?php echo _l('options'); ?></th>
		</thead>
		<tbody>
			<?php foreach($salary_form as $c){ ?>
				<tr>
					<td><?php echo new_html_entity_decode($c['form_name']); ?></td>
					<!-- <td><?php echo app_format_money($c['salary_val'],''); ?></td> -->
											<td>
							<?php
              if ($c['calculation_type'] == 0) {
							echo 'Flat';
						} elseif ($c['calculation_type'] == 1) {
							echo '% of Basic';
						}
						elseif ($c['calculation_type'] == 2) {
							echo '% of CTC';
						}							?>
						</td>
										<td>
					<?php
						if ($c['calculation_type'] == 0) {
							echo $c['salary_val'];
						} elseif ($c['calculation_type'] == 1) {
							echo $c['salary_val'] . ' %';
						}
						elseif ($c['calculation_type'] == 2) {
							echo $c['salary_val'] . ' %';
						}
					?>
					</td>

					<td class="hide"><?php if($c['tax'] == 0){echo _l('no');}else{echo _l('yes');}?></td>
					<td>

						<?php if(has_permission('hrm_setting', '', 'edit') || is_admin() ){ ?>
						<a href="#" 
							onclick="edit_salary_form(this, <?php echo $c['form_id']; ?>); return false"
							data-name="<?php echo $c['form_name']; ?>"
							data-salary="<?php echo $c['salary_val']; ?>"
							data-calculation-type="<?php echo $c['calculation_type']; ?>"
							data-active="<?php echo $c['active']; ?>"
							data-is-part-of-structure="<?php echo $c['is_part_of_structure']; ?>"
							data-is-tax-deducted-monthly="<?php echo $c['is_tax_deducted_monthly']; ?>"
							data-is-prorated="<?php echo $c['is_prorated']; ?>"
							data-consider-for-epf="<?php echo $c['consider_for_epf']; ?>"
							data-consider-for-esi="<?php echo $c['consider_for_esi']; ?>"
							data-show-in-payslip="<?php echo $c['show_in_payslip']; ?>"
							class="btn btn-default btn-icon">
							<i class="fa-regular fa-pen-to-square"></i>
						</a>


						<?php } ?>

						<?php if((has_permission('hrm_setting', '', 'delete') || is_admin()) && $c['form_id'] > 35): ?>
    <a href="<?php echo admin_url('hr_profile/delete_salary_form/'.$c['form_id']); ?>" class="btn btn-danger btn-icon _delete">
        <i class="fa fa-remove"></i>
    </a>
<?php endif; ?>

					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>  




<!-- Modal -->
<div class="modal" id="salary_form" tabindex="-1" role="dialog">
  <div class="modal-dialog w-25">
    <?php echo form_open(admin_url('hr_profile/salary_form'), ['id' => 'add_salary_form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">
          <span class="edit-title"><?php echo _l('hr_edit_salary_form'); ?></span>
          <span class="add-title"><?php echo _l('hr_new_salary_form'); ?></span>
        </h4>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional_salary_form"></div>
            <div class="form">

              <?php echo render_input('form_name', 'hr_salary_form_name'); ?>

              <!-- Pay Type -->
              <div class="form-group">
                <label for="pay_type" class="control-label">Pay Type</label>
                <select name="pay_type" id="pay_type" class="form-control selectpicker" data-width="100%">
                  <option value="Fixed">Fixed</option>
                  <option value="Variable">Variable</option>
                </select>
              </div>

              <!-- Calculation Type -->
              <div class="form-group">
                <label for="calculation_type" class="control-label">Calculation Type</label>
                <select name="calculation_type" id="calculation_type" class="form-control selectpicker" data-width="100%">
                  <option value="0">Flat</option>
                  <option value="1">Percentage of Basic</option>
                  <option value="2">Percentage of CTC</option>
                </select>
              </div>

              <!-- Salary Value -->
              <div class="form-group" id="salary_val_wrapper">
                <?php echo render_input('salary_val', 'Amount', '', 'decimal', ['id' => 'salary_val', 'step' => '0.1']); ?>
              </div>

              <!-- Checkboxes -->
              <?php
              $checkboxes = [
                'active' => 'Active',
                'is_part_of_structure' => 'Is Part of Salary Structure',
                'is_tax_deducted_monthly' => 'Tax Deducted Monthly',
                'is_prorated' => 'Is Prorated',
                'consider_for_epf' => 'Consider for EPF',
                'consider_for_esi' => 'Consider for ESI',
                'show_in_payslip' => 'Show in Payslip'
              ];

              foreach ($checkboxes as $name => $label) {
                echo '<div class="form-check">';
                echo '<label class="form-check-label">';
                echo '<input type="checkbox" name="' . $name . '" id="' . $name . '" value="1" class="form-check-input" checked>';
                echo $label;
                echo '</label>';
                if ($name == 'is_tax_deducted_monthly') {
                  echo '<p>The income tax amount will be divided equally and deducted every month across the financial year.</p>';
                }
                if ($name == 'is_prorated') {
                  echo '<p>Pay will be adjusted based on employee working days.</p>';
                }
                echo '</div>';
              }
              ?>

            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('hr_close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>
<!-- /.modal -->

</div>

<?php
?>


</body>
</html>
