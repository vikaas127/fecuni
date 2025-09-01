<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="panel_s">
  <div class="panel-body">
    <div class="row">
      <div class="col-md-12">
        <h4 class="h4-color no-margin">
          <i class="fa-solid fa-hand-holding-dollar" aria-hidden="true"></i>
          <?php echo _l('reimbursement_list'); ?>
        </h4>
      </div>
    </div>
    <hr class="hr-color">

    <a href="javascript:void(0);" onclick="new_reimbursement_form();" class="btn btn-primary mb-3">
      <?php echo _l('add'); ?>
    </a>

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead style="background-color:rgb(234, 229, 229);">
          <tr>
            <th><?php echo _l('reimbursement_type'); ?></th>
            <th><?php echo _l('name_in_payslip'); ?></th>
            <th><?php echo _l('is_fbp_component'); ?></th>
            <th><?php echo _l('restrict_fbp_override'); ?></th>
            <th><?php echo _l('unclaimed_handling'); ?></th>
            <th><?php echo _l('max_monthly_amount'); ?></th>
            <th><?php echo _l('is_active'); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php $reimbursement_list = json_decode($reimbursement_list, true); ?>
          <?php foreach ($reimbursement_list as $row): ?>
            <tr>
              <td>
                <strong><?php echo html_escape($row['reimbursement_type']); ?></strong>
                <div class="text-muted small mt-1">
                  <a href="javascript:void(0);" onclick="view_reimbursement_form(this, <?php echo $row['id']; ?>)"
                    data-id="<?php echo $row['id']; ?>"
                    data-reimbursement_type="<?php echo html_escape($row['reimbursement_type']); ?>"
                    data-name_in_payslip="<?php echo html_escape($row['name_in_payslip']); ?>"
                    data-is_fbp_component="<?php echo $row['is_fbp_component']; ?>"
                    data-restrict_fbp_override="<?php echo $row['restrict_fbp_override']; ?>"
                    data-unclaimed_handling="<?php echo $row['unclaimed_handling']; ?>"
                    data-max_monthly_amount="<?php echo $row['max_monthly_amount']; ?>"
                    data-is_active="<?php echo $row['is_active']; ?>"
                  ><?php echo _l('view'); ?></a> |
                  <a href="javascript:void(0);" onclick="edit_reimbursement_form(this, <?= $row['id']; ?>)"
                  data-reimbursement_type="<?= $row['reimbursement_type']; ?>"
                  data-name_in_payslip="<?= $row['name_in_payslip']; ?>"
                  data-is_fbp_component="<?= $row['is_fbp_component']; ?>"
                  data-restrict_fbp_override="<?= $row['restrict_fbp_override']; ?>"
                  data-unclaimed_handling="<?= $row['unclaimed_handling']; ?>"
                  data-max_monthly_amount="<?= $row['max_monthly_amount']; ?>"
                  data-is_active="<?= $row['is_active']; ?>"

                  ><?php echo _l('edit'); ?></a> |
                  <a href="<?php echo admin_url('hr_payroll/delete_reimbursement/' . $row['id']); ?>" class="text-danger _delete">
                    <?php echo _l('delete'); ?>
                  </a>
                </div>
              </td>
              <td><?php echo html_escape($row['name_in_payslip']); ?></td>
              <td><?php echo $row['is_fbp_component'] ? _l('yes') : _l('no'); ?></td>
              <td><?php echo $row['restrict_fbp_override'] ? _l('yes') : _l('no'); ?></td>
              <td><?php echo _l($row['unclaimed_handling']); ?></td>
              <td><?php echo app_format_money($row['max_monthly_amount'], ''); ?></td>
              <td><?php echo $row['is_active'] ? _l('yes') : _l('no'); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="add_reimbursement_modal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <?php echo form_open(admin_url('hr_payroll/setting_reimbursement_list'), ['id' => 'reimbursement_form']); ?>
          <div class="modal-header">
            <h5 class="modal-title">
              <span class="add-title"><?php echo _l('add_reimbursement'); ?></span>
              <span class="edit-title hide"><?php echo _l('edit_reimbursement'); ?></span>
              <span class="view-title hide"><?php echo _l('view_reimbursement'); ?></span>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <input type="hidden" name="id" id="reimbursement_id">
            <div class="row">
              <div class="col-md-6">
                <label for="reimbursement_type"><?php echo _l('reimbursement_type'); ?></label>
                <select name="reimbursement_type" id="reimbursement_type" class="form-control selectpicker">
                  <?php foreach ($reimbursement_types as $type): ?>
                    <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <?php echo render_input('name_in_payslip', 'name_in_payslip'); ?>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-md-6">
                <label for="unclaimed_handling"><?php echo _l('unclaimed_handling'); ?></label>
                <select name="unclaimed_handling" id="unclaimed_handling" class="form-control selectpicker">
                  <?php foreach ($unclaimed_handling_options as $key => $label): ?>
                    <option value="<?php echo $key; ?>"><?php echo $label; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <?php echo render_input('max_monthly_amount', 'max_monthly_amount', '', 'number'); ?>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-md-3">
                <label><input type="checkbox" name="is_fbp_component" id="is_fbp_component" value="1"> <?php echo _l('is_fbp_component'); ?></label>
              </div>
              <div class="col-md-3">
                <label><input type="checkbox" name="restrict_fbp_override" id="restrict_fbp_override" value="1"> <?php echo _l('restrict_fbp_override'); ?></label>
              </div>
              <div class="col-md-3">
                <label><input type="checkbox" name="is_active" id="is_active" value="1" checked> <?php echo _l('is_active'); ?></label>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-success"> <?php echo _l('submit'); ?> </button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal"> <?php echo _l('close'); ?> </button>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>

  </div>
</div>
