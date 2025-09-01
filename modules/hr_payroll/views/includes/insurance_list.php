<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="panel_s">
  <div class="panel-body">
    <div class="row">
      <div class="col-md-12">
        <h4 class="h4-color no-margin">
          <i class="fa-brands fa-get-pocket" aria-hidden="true"></i> 
          <?php echo _l('insurance_list'); ?>
        </h4>
      </div>
    </div>
    <hr class="hr-color">

    <!-- Add New Button -->
    <a href="javascript:void(0);" onclick="new_insurance_form();" class="btn btn-primary mb-3">
      <?php echo _l('add'); ?>
    </a>

    <!-- Table -->
    <div class="table-responsive">
      <table class="table table-bordered" >
        <thead style="background-color:rgb(234, 229, 229);">
          <tr >
            <th ><?php echo _l('description'); ?></th>
            <th><?php echo _l('code'); ?></th>
            <th><?php echo _l('rate'); ?></th>
            <th><?php echo _l('basis'); ?></th>
            <th><?php echo _l('benefit_plan_type'); ?></th>
            <th><?php echo _l('associated_section'); ?></th>
            <th><?php echo _l('is_active'); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php
$insurance_list = json_decode($insurance_list, true);
?>

        <?php foreach ($insurance_list as $row): ?>
  <tr>
    <!-- DESCRIPTION column with action links below -->
   <td>
  <strong><?php echo html_escape($row['description']); ?></strong>
  <div class="text-muted small mt-1">

    <!-- View link with data-* attributes -->
    <a href="javascript:void(0);" 
      onclick="view_insurance_form(this, <?php echo $row['id']; ?>)" 
      data-id="<?php echo $row['id']; ?>"
      data-code="<?php echo html_escape($row['code']); ?>"
      data-description="<?php echo html_escape($row['description']); ?>"
      data-rate="<?php echo html_escape($row['rate']); ?>"
      data-basis="<?php echo html_escape($row['basis']); ?>"
      data-benefit-type="<?php echo html_escape($row['benefit_plan_type']); ?>"
      data-associated-section='<?php echo html_escape($row['associated_section']); ?>'
      data-include-employer-contribution="<?php echo $row['include_employer_contribution']; ?>"
      data-is-superannuation-fund="<?php echo $row['is_superannuation_fund']; ?>"
      data-calculate-pro-rata="<?php echo $row['calculate_pro_rata']; ?>"
      data-is-active="<?php echo $row['is_active']; ?>"
    >
      <?php echo _l('view'); ?>
    </a> |

    <!-- Edit link (unchanged) -->
    <a href="javascript:void(0);" 
      onclick="edit_insurance_form(this, <?php echo $row['id']; ?>)" 
      data-id="<?php echo $row['id']; ?>"
      data-code="<?php echo html_escape($row['code']); ?>"
      data-description="<?php echo html_escape($row['description']); ?>"
      data-rate="<?php echo html_escape($row['rate']); ?>"
      data-basis="<?php echo html_escape($row['basis']); ?>"
      data-benefit-type="<?php echo html_escape($row['benefit_plan_type']); ?>"
      data-associated-section='<?php echo html_escape($row['associated_section']); ?>'
      data-include-employer-contribution="<?php echo $row['include_employer_contribution']; ?>"
      data-is-superannuation-fund="<?php echo $row['is_superannuation_fund']; ?>"
      data-calculate-pro-rata="<?php echo $row['calculate_pro_rata']; ?>"
      data-is-active="<?php echo $row['is_active']; ?>"
    >
      <?php echo _l('edit'); ?>
    </a> |

    <!-- Delete link -->
    <a href="<?php echo admin_url('hr_payroll/delete_insurance/' . $row['id']); ?>" 
       class="text-danger _delete">
      <?php echo _l('delete'); ?>
    </a>

  </div>
</td>


    <!-- Other table columns -->
    <td><?php echo html_escape($row['code']); ?></td>
    <td><?php echo html_escape($row['rate']); ?></td>
    <td><?php echo html_escape($row['basis']); ?></td>
    <td><?php echo html_escape($row['benefit_plan_type']); ?></td>
    <td>
      <?php
        $sections = json_decode($row['associated_section'], true);
        echo is_array($sections) ? implode(', ', $sections) : '';
      ?>
    </td>
    <td><?php echo $row['is_active'] ? _l('yes') : _l('no'); ?></td>
  </tr>
<?php endforeach; ?>

        </tbody>
      </table>
    </div>

    <!-- Add/Edit Insurance Modal -->
    <div class="modal fade" id="add_insurance_modal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <?php echo form_open(admin_url('hr_payroll/setting_insurance_list'), ['id' => 'insurance_form']); ?>
          <div class="modal-header">
          <h5 class="modal-title">
  <span class="add-title"><?php echo _l('add_insurance'); ?></span>
  <span class="edit-title hide"><?php echo _l('edit_insurance'); ?></span>
  <span class="view-title hide"><?php echo _l('view_insurance'); ?></span>
</h5>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <input type="hidden" name="id" id="plan_id">

            <div class="row">
              <div class="col-md-6">
                <?php echo render_input('code', 'code'); ?>
              </div>
              <div class="col-md-6">
                <?php echo render_input('description', 'description'); ?>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <?php echo render_input('rate', 'rate', '', 'number'); ?>
              </div>
              <div class="col-md-6">
                <label for="basis"><?php echo _l('basis'); ?></label>
                <select name="basis" id="basis" class="form-control selectpicker" data-live-search="true">
                  <?php foreach ($basis_value as $basis): ?>
                    <option value="<?php echo $basis['id']; ?>"><?php echo $basis['label']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="form-group mt-3">
              <label for="benefit_plan_type"><?php echo _l('benefit_plan_type'); ?></label>
              <select name="benefit_plan_type" id="benefit_plan_type" class="form-control selectpicker">
                <option value=""><?php echo _l('select'); ?></option>
                <option value="NPS">NPS</option>
                <option value="Other Non-Taxable Deduction">Other Non-Taxable Deduction</option>
              </select>
            </div>

            <div class="form-group mt-3" id="associated_section_wrapper">
              <label for="associated_section"><?php echo _l('associated_section'); ?></label>
              <select name="associated_section[]" id="associated_section" class="form-control selectpicker" multiple data-live-search="true">
                <?php foreach ($associated_sections as $group => $options): ?>
                  <optgroup label="<?php echo $group; ?>">
                    <?php foreach ($options as $option): ?>
                      <option value="<?php echo $group . ' - ' . $option; ?>"><?php echo $option; ?></option>
                    <?php endforeach; ?>
                  </optgroup>
                <?php endforeach; ?>
              </select>
            </div>

          <div class="row">
  <div class="col-md-6 form-check">
    <label>
      <input type="checkbox" name="include_employer_contribution" id="include_employer_contribution" value="1">
      <?php echo _l('include_employer_contribution'); ?>
    </label>
  </div>

  <div class="col-md-6 form-check">
    <label>
      <input type="checkbox" name="is_superannuation_fund" id="is_superannuation_fund" value="1">
      <?php echo _l('is_superannuation_fund'); ?>
    </label>
  </div>

  <div class="col-md-6 form-check">
    <label>
      <input type="checkbox" name="calculate_pro_rata" id="calculate_pro_rata" value="1">
      <?php echo _l('calculate_pro_rata'); ?>
    </label>
  </div>

  <div class="col-md-6 form-check">
    <label>
      <input type="checkbox" name="is_active" id="is_active" value="1" checked>
      <?php echo _l('is_active'); ?>
    </label>
  </div>
</div>

          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-success"><?php echo _l('submit'); ?></button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo _l('close'); ?></button>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>

  </div>
</div>
