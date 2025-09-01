
    <div class="row">
				<div class="col-md-3">
					<?php if($member->staffid == get_staff_user_id() || has_permission('hrm_hr_records', '', 'create') || has_permission('hrm_hr_records', '', 'edit')){ ?>
						<div class="_buttons">
							

                        <a href="#" onclick="add_edit_investments(<?= $member->staffid ?>); return false;" class="btn btn-primary pull-left display-block">
                            <?php echo _l('hr_investments'); ?>
                        </a>



						</div>
					<?php } ?>
				</div>
                  
<div class="col-md-3">
  <?php if ($member->staffid == get_staff_user_id() || has_permission('hrm_hr_records', '', 'create') || has_permission('hrm_hr_records', '', 'edit')) { ?>
    <div class="_buttons">
      <button class="btn btn-info"
        onclick="showTaxModal(<?= $member->staffid ?>, <?= $declaration['id'] ?>, '<?= $declaration['financial_year'] ?>')">
        View Tax Calculation
      </button>
    </div>
  <?php } ?>
</div>
	</div>
 



 
			<div class="modal fade" id="investments" tabindex="-1" role="dialog">
				<div class="modal-dialog">
					<?php echo form_open(admin_url('hr_profile/investments'),['id' => 'investmentsForm']);  ?>

       
                                        <?php if (isset($member) && $member->staffid != get_staff_user_id()) { ?>
                                                                                <input type="hidden" name="staffid" value="<?php echo html_escape($member->staffid); ?>">
										<?php } ?>
                                        <?php if (!empty($declaration) && isset($declaration['id'])) : ?>
                <input type="hidden" name="declaration_id" value="<?= html_escape($declaration['id']) ?>">
                                           <?php endif; ?>



					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">
								<span class="edit-title"><?php echo _l('hr_edit_investments'); ?></span>
								<span class="add-title"><?php echo _l('hr_add_investments'); ?></span>
							</h4>
						</div>
						<div class="modal-body">
							<div class="row ">
         

                            

                                <!-- HRA -->
                                <div class="form-group">
                                    <label>Are you staying in a rented house?</label>
                                    <select class="form-control" name="declares_hra" id="declares_hra" onchange="toggleHRA()">
                                    <option value="No" <?= $declaration['declares_hra'] === 'No' ? 'selected' : '' ?>>No</option>
                                    <option value="Yes" <?= $declaration['declares_hra'] === 'Yes' ? 'selected' : '' ?>>Yes</option>
                                    </select>
                                </div>

                                <div id="hra-wrapper" style="display: <?= $declaration['declares_hra'] === 'Yes' ? 'block' : 'none' ?>;">
                                    <h4>HRA Details</h4>
                                    <div id="hra-container">
                                    <?php foreach ($hra_data as $index => $hra) { ?>
                                        <div class="hra-group border p-3 mb-3 position-relative" data-id="<?= $hra['id'] ?>">
                                        <input type="hidden" name="hra[<?= $index ?>][id]" value="<?= $hra['id'] ?>">
                                        <button type="button" class="remove-btn btn btn-link text-danger" onclick="removeGroup(this, 'hra')">&times;</button>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                            <label>Rental From</label>
                                            <input type="month" class="form-control" name="hra[<?= $index ?>][rental_from]" value="<?= $hra['rental_from'] ?>">
                                            </div>
                                            <div class="col-md-6">
                                            <label>Rental To</label>
                                            <input type="month" class="form-control" name="hra[<?= $index ?>][rental_to]" value="<?= $hra['rental_to'] ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label>Metro City?</label>
                                                <select class="form-control" name="hra[<?= $index ?>][is_metro]">
                                                    <option value="1" <?= (isset($hra['is_metro']) && $hra['is_metro'] == '1') ? 'selected' : '' ?>>
                                                        Yes (Metro)
                                                    </option>
                                                    <option value="0" <?= (!isset($hra['is_metro']) || $hra['is_metro'] == '0') ? 'selected' : '' ?>>
                                                        No (Non-Metro)
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                            <label>Monthly Rent</label>
                                            <input type="number" class="form-control" name="hra[<?= $index ?>][monthly_rent]" value="<?= $hra['monthly_rent'] ?>">
                                            </div>
                                            <div class="col-md-4">
                                            <label>Landlord Name</label>
                                            <input type="text" class="form-control" name="hra[<?= $index ?>][landlord_name]" value="<?= $hra['landlord_name'] ?>">
                                            </div>
                                            <div class="col-md-4">
                                            <label>Landlord PAN</label>
                                            <input type="text" class="form-control" name="hra[<?= $index ?>][landlord_pan]" value="<?= $hra['landlord_pan'] ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label>Address</label>
                                            <textarea class="form-control" name="hra[<?= $index ?>][address]"><?= $hra['address'] ?></textarea>
                                        </div>
                                        </div>
                                    <?php } ?>
                                    </div>
                                    <!-- HRA Template -->
                                    <template id="hra-template">
                                    <div class="hra-group border p-3 mb-3 position-relative">
                                        <input type="hidden" name="hra[__index__][id]" value="">
                                        <button type="button" class="remove-btn btn btn-link text-danger" onclick="removeGroup(this, 'hra')">&times;</button>
                                        
                                        <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Rental From</label>
                                            <input type="month" class="form-control" name="hra[__index__][rental_from]" value="">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Rental To</label>
                                            <input type="month" class="form-control" name="hra[__index__][rental_to]" value="">
                                        </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <select class="form-control" name="hra[__index__][is_metro]">
                                        <option value="1" <?php echo (isset($hra['is_metro']) && $hra['is_metro'] == '1') ? 'selected' : ''; ?>>
                                            Yes (Metro)
                                        </option>
                                        <option value="0" <?php echo (!isset($hra['is_metro']) || $hra['is_metro'] == '0') ? 'selected' : ''; ?>>
                                            No (Non-Metro)
                                        </option>
                                    </select>

                                            </div>
                                        <div class="col-md-4">
                                            <label>Monthly Rent</label>
                                            <input type="number" class="form-control" name="hra[__index__][monthly_rent]" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Landlord Name</label>
                                            <input type="text" class="form-control" name="hra[__index__][landlord_name]" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Landlord PAN</label>
                                            <input type="text" class="form-control" name="hra[__index__][landlord_pan]" value="">
                                        </div>
                                        </div>

                                        <div class="mb-3">
                                        <label>Address</label>
                                        <textarea class="form-control" name="hra[__index__][address]"></textarea>
                                        </div>
                                    </div>
                                    </template>







                                    <button type="button" class="btn btn-info" onclick="addHRA()">+ Add More</button>
                                    <hr>
                                </div>


                                <!-- Home Loan -->
                                <div class="form-group">
                                    <label>Are you repaying a home loan?</label>
                                    <select class="form-control" name="declares_home_loan" id="declares_home_loan" onchange="toggleLoan()">
                                    <option value="No" <?= $declaration['declares_home_loan'] === 'No' ? 'selected' : '' ?>>No</option>
                                    <option value="Yes" <?= $declaration['declares_home_loan'] === 'Yes' ? 'selected' : '' ?>>Yes</option>
                                    </select>
                                </div>

                                        <div id="loan-wrapper" style="display: <?= $declaration['declares_home_loan'] === 'Yes' ? 'block' : 'none' ?>;">
                                            <h4>Home Loan Details</h4>
                                            <div id="loan-container">
                                            <?php foreach ($loan_data as $index => $loan) { ?>
                                                <div class="loan-group border p-3 mb-3 position-relative" data-id="<?= $loan['id'] ?>">
                                                <input type="hidden" name="loans[<?= $index ?>][id]" value="<?= $loan['id'] ?>">
                                                <button type="button" class="remove-btn btn btn-link text-danger" onclick="removeGroup(this, 'loan')">&times;</button>
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                    <label>Lender Name</label>
                                                    <input type="text" class="form-control" name="loans[<?= $index ?>][lender_name]" value="<?= $loan['lender_name'] ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                    <label>Loan Start Date</label>
                                                    <input type="date" class="form-control" name="loans[<?= $index ?>][loan_start_date]" value="<?= $loan['loan_start_date'] ?>">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                    <label>Principal Paid</label>
                                                    <input type="number" class="form-control" name="loans[<?= $index ?>][principal_paid]" value="<?= $loan['principal_paid'] ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                    <label>Annual Interest</label>
                                                    <input type="number" class="form-control" name="loans[<?= $index ?>][annual_interest]" value="<?= $loan['annual_interest'] ?>">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Property Address</label>
                                                    <textarea class="form-control" name="loans[<?= $index ?>][property_address]"><?= $loan['property_address'] ?></textarea>
                                                </div>
                                                </div>
                                            <?php } ?>
                                            </div>
                                            <!-- Loan Template -->
                                            <template id="loan-template">
                                            <div class="loan-group border p-3 mb-3 position-relative">
                                                <input type="hidden" name="loans[__index__][id]" value="">
                                                <button type="button" class="remove-btn btn btn-link text-danger" onclick="removeGroup(this, 'loan')">&times;</button>

                                                <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label>Lender Name</label>
                                                    <input type="text" class="form-control" name="loans[__index__][lender_name]" value="">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Loan Start Date</label>
                                                    <input type="date" class="form-control" name="loans[__index__][loan_start_date]" value="">
                                                </div>
                                                </div>

                                                <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label>Principal Paid</label>
                                                    <input type="number" class="form-control" name="loans[__index__][principal_paid]" value="">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Annual Interest</label>
                                                    <input type="number" class="form-control" name="loans[__index__][annual_interest]" value="">
                                                </div>
                                                </div>

                                                <div class="mb-3">
                                                <label>Property Address</label>
                                                <textarea class="form-control" name="loans[__index__][property_address]"></textarea>
                                                </div>
                                            </div>
                                            </template>

                                            <button type="button" class="btn btn-info" onclick="addLoan()">+ Add More</button>
                                            <hr>
                                        </div>

                                      

                                              
                                       <!-- Other Investment Declarations -->
                        <h4>Other Investment Declarations</h4>
                        <div id="investment-container">
                            <?php foreach ($investment_data as $index => $inv) { ?>
                                <div class="investment-group border p-3 mb-3 position-relative" data-id="<?= $inv['id'] ?>">
                                    <input type="hidden" name="investments[<?= $index ?>][id]" value="<?= $inv['id'] ?>">
                                    <button type="button" class="remove-btn btn btn-link text-danger" onclick="removeGroup(this, 'investment')">&times;</button>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label>Section</label>
                                            <select class="form-control" name="investments[<?= $index ?>][section]" onchange="updateTypes(this)">
                                                <option value="">-- Select Section --</option>
                                                <?php foreach ($investment_sections as $code => $label) { ?>
                                                    <option value="<?= $code ?>" <?= ($inv['section'] ?? '') === $code ? 'selected' : '' ?>>
                                                        <?= $label ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Investment Type</label>
                                            <select class="form-control"
                                                    name="investments[<?= $index ?>][investment_type]"
                                                    id="investment_type_<?= $index ?>"
                                                    data-selected="<?= htmlspecialchars($inv['investment_type']) ?>">
                                                <option value="">-- Select Investment Type --</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Amount</label>
                                            <input type="number" class="form-control" name="investments[<?= $index ?>][declared_amount]" value="<?= $inv['declared_amount'] ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <!-- Investment Template for JS cloning -->
                        <template id="investment-template">
                            <div class="investment-group border p-3 mb-3 position-relative">
                                <input type="hidden" name="investments[__index__][id]" value="">
                                <button type="button" class="remove-btn btn btn-link text-danger" onclick="removeGroup(this, 'investment')">&times;</button>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label>Section</label>
                                        <select class="form-control" name="investments[__index__][section]" onchange="updateTypes(this)">
                                            <option value="">-- Select Section --</option>
                                            <?php foreach ($investment_sections as $key => $label): ?>
                                                <option value="<?= $key ?>"><?= $label ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Investment Type</label>
                                        <select class="form-control"
                                                name="investments[__index__][investment_type]"
                                                id="investment_type___index__"
                                                data-selected="">
                                            <option value="">-- Select Investment Type --</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Amount</label>
                                        <input type="number" class="form-control" name="investments[__index__][declared_amount]" value="">
                                    </div>
                                </div>
                            </div>
                        </template>

                                       <button type="button" class="btn btn-info" onclick="addInvestment()">+ Add More</button>


                                                                    <!-- Hidden fields for removed IDs -->
                                                                    <input type="hidden" name="removed_hra_ids" id="removed_hra_ids">
                                                                    <input type="hidden" name="removed_loan_ids" id="removed_loan_ids">
                                                                    <input type="hidden" name="removed_investment_ids" id="removed_investment_ids">
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


<!-- Tax Regime Modal -->
<div class="modal fade" id="taxModal" tabindex="-1" role="dialog" aria-labelledby="taxModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <!-- Hidden fields to store values -->
      <input type="hidden" name="employee_id" id="tax_employee_id" value="">
      <input type="hidden" name="declaration_id"id="tax_declaration_id" value="">
      <input type="hidden" name="financial_year" id="tax_financial_year" value="">

      <div class="modal-header">
        <h4 class="modal-title" id="taxModalLabel"><?php echo _l('Tax Calculation & Regime Selection'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" id="taxCalculationContent">
        <p><?php echo _l('Loading tax details...'); ?></p>
      </div>

     <div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('Close'); ?></button>
  <button type="button" class="btn btn-primary" onclick="saveSelectedRegime()"><?php echo _l('Save Regime'); ?></button>
</div>

    </div>
  </div>
</div>







<div class="row">
  	<div class="panel_s">
			<div class="panel-body">
        		<div class="row">
				   <!-- HRA -->
                    <div class="form-group">
                        <label>Are you staying in a rented house?</label>
                        <input type="text" class="form-control" value="<?= isset($hra_data) && !empty($hra_data) ? 'Yes' : 'No'; ?>" readonly>
                    </div>

                    <?php if (!empty($hra_data)): ?>
                    <div id="hra-wrapper">
                        <h4>HRA Details</h4>
                        <div id="hra-container">
                            <?php foreach ($hra_data as $index => $hra): ?>
                            <div class="hra-group border p-3 mb-3 position-relative">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label>Rental From</label>
                                        <input type="month" class="form-control" value="<?= $hra['rental_from']; ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Rental To</label>
                                        <input type="month" class="form-control" value="<?= $hra['rental_to']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
    <label>Metro City?</label>
    <input type="text" class="form-control" 
           value="<?= (isset($hra['is_metro']) && $hra['is_metro'] == '1') ? 'Yes (Metro)' : 'No (Non-Metro)'; ?>" 
           readonly>
</div>

                                    <div class="col-md-4">
                                        <label>Monthly Rent</label>
                                        <input type="number" class="form-control" value="<?= $hra['monthly_rent']; ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Landlord Name</label>
                                        <input type="text" class="form-control" value="<?= $hra['landlord_name']; ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Landlord PAN</label>
                                        <input type="text" class="form-control" value="<?= $hra['landlord_pan']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label>Address</label>
                                    <textarea class="form-control" readonly><?= $hra['address']; ?></textarea>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Home Loan -->
                    <div class="form-group">
                        <label>Are you repaying a home loan?</label>
                        <input type="text" class="form-control" value="<?= isset($loan_data) && !empty($loan_data) ? 'Yes' : 'No'; ?>" readonly>
                    </div>

                    <?php if (!empty($loan_data)): ?>
                            <div id="loan-wrapper">
                                <h4>Home Loan Details</h4>
                                <div id="loan-container">
                                    <?php foreach ($loan_data as $index => $loan): ?>
                                    <div class="loan-group border p-3 mb-3 position-relative">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label>Lender Name</label>
                                                <input type="text" class="form-control" value="<?= $loan['lender_name']; ?>" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Loan Start Date</label>
                                                <input type="date" class="form-control" value="<?= $loan['loan_start_date']; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label>Principal Paid</label>
                                                <input type="number" class="form-control" value="<?= $loan['principal_paid']; ?>" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Annual Interest</label>
                                                <input type="number" class="form-control" value="<?= $loan['annual_interest']; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label>Property Address</label>
                                            <textarea class="form-control" readonly><?= $loan['property_address']; ?></textarea>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                    <?php endif; ?>
                    <!-- Investments -->
                    <?php if (!empty($investment_data)): ?>
                        <h4>Other Investment Declarations</h4>
                        <div id="investment-wrapper">
                            <div id="investment-container">
                                <?php foreach ($investment_data as $index => $investment): ?>
                                <div class="investment-group border p-3 mb-3 position-relative">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label>Section</label>
                                            <input type="text" class="form-control" value="<?= $investment['section']; ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Investment Type</label>
                                            <input type="text" class="form-control" value="<?= $investment['investment_type']; ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Amount (₹)</label>
                                            <input type="number" class="form-control" value="<?= $investment['declared_amount']; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>


                </div>
              


	

            </div>
    </div>
</div>