<script>
	"use strict";
	
	appValidateForm($("body").find('#add_update_work_center'), {
		'work_center_name': 'required',
	});    
	$(function () {

	  // template – will be cloned when + pressed
	  function machineTemplate(){
	    return `
	    <div class="machine-row panel_s p15 m-b-10">
	      <div class="row">
	        <div class="col-md-6"><?php echo render_input('machine_name[]','machine_name','', 'text', [], [], true); ?></div>
	        <div class="col-md-6"><?php echo render_input('machine_code[]','machine_code',$m['machine_code']); ?></div>
	        <div class="col-md-6"><?php echo render_input('costs_hour[]','costs_hour','', 'number', [], [], true); ?></div>
	        <div class="col-md-6"><?php echo render_input('capacity[]','work_center_capacity','', 'number', [], [], true); ?></div>
	        <div class="col-md-6"><?php echo render_input('oee_target[]','oee_target','', 'number', [], [], true); ?></div>
	        <div class="col-md-6"><?php echo render_select('staff_id[]', $staffs, array('staffid', array('firstname', 'lastname')), 'Assigned Operator', $staff_id_selected, [], [], '', '', false); ?></div>
	        <div class="col-md-6">
			  <label for="status"><?php echo _l('status'); ?></label>
			  <select name="status[]" class="form-control selectpicker">
			    <option value="active" <?php echo (isset($m['status']) && $m['status'] == 'active') ? 'selected' : ''; ?>><?php echo _l('active'); ?></option>
			    <option value="idle" <?php echo (isset($m['status']) && $m['status'] == 'idle') ? 'selected' : ''; ?>><?php echo _l('idle'); ?></option>
			    <option value="maintenance" <?php echo (isset($m['status']) && $m['status'] == 'maintenance') ? 'selected' : ''; ?>><?php echo _l('maintenance'); ?></option>
			    <option value="offline" <?php echo (isset($m['status']) && $m['status'] == 'offline') ? 'selected' : ''; ?>><?php echo _l('offline'); ?></option>
			  </select>
			</div>
	        <div class="col-md-12 text-right mtop15">
	          <button type="button" class="btn btn-danger btn-sm remove-machine"><i class="fa fa-times"></i></button>
	        </div>
	      </div>
	    </div>`;
	  }

	  // add machine
	  $('.machine-wrapper').on('click','.add-machine',function(){
	      $('.machine-wrapper').append(machineTemplate());
	          init_selectpicker();
	  });

	  // remove machine
	  $('.machine-wrapper').on('click','.remove-machine',function(){
	      $(this).closest('.machine-row').remove();
	  });

	});

</script>