<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php 
			$id = '';
			$title = '';
			if(isset($work_center)){
				$title .= _l('update_work_center');
				$id    = $work_center->id;
			}else{
				$title .= _l('add_work_center');
			}

			?>

			<?php echo form_open_multipart(admin_url('manufacturing/add_edit_work_center/'.$id), array('id' => 'add_update_work_center','autocomplete'=>'off')); ?>

			<div class="col-md-12" >
				<div class="panel_s">
					
					<div class="panel-body">
						<div class="row mb-5">
							<div class="col-md-5">
								<h4 class="no-margin"><?php echo new_html_entity_decode($title); ?> 
							</div>
							<div class="col-md-7">

								<div class="o_not_full oe_button_box"><button type="button" name="240" class="btn oe_stat_button"><i class="fa fa-fw o_button_icon fa-pie-chart"></i><div class="o_field_widget o_stat_info"><span class="o_stat_value"><div name="oee" class="o_field_widget o_stat_info o_readonly_modifier" data-original-title="" title="">
									<span class="o_stat_value">0.00</span>
									<span class="o_stat_text"></span>
								</div>%</span><span class="o_stat_text">OEE</span></div></button><button type="button" name="241" class="btn oe_stat_button"><i class="fa fa-fw o_button_icon fa-bar-chart"></i><div class="o_field_widget o_stat_info"><span class="o_stat_value"><div name="blocked_time" class="o_field_widget o_stat_info o_readonly_modifier" data-original-title="" title="">
									<span class="o_stat_value">0.00</span>
									<span class="o_stat_text"></span>
								</div> Hours</span><span class="o_stat_text">Lost</span></div></button><button type="button" name="237" class="btn oe_stat_button" context="{'search_default_workcenter_id': id}"><i class="fa fa-fw o_button_icon fa-bar-chart"></i><div class="o_field_widget o_stat_info"><span class="o_stat_value"><div name="workcenter_load" class="o_field_widget o_stat_info o_readonly_modifier">
									<span class="o_stat_value">0.00</span>
									<span class="o_stat_text"></span>
								</div> Minutes</span><span class="o_stat_text">Load</span></div></button><button type="button" name="243" class="btn oe_stat_button" context="{'search_default_workcenter_id': id, 'search_default_thisyear': True}"><i class="fa fa-fw o_button_icon fa-bar-chart"></i><div class="o_field_widget o_stat_info"><span class="o_stat_value"><div name="performance" class="o_field_widget o_stat_info o_readonly_modifier" data-original-title="" title="">
									<span class="o_stat_value">0</span>
									<span class="o_stat_text"></span>
								</div>%</span><span class="o_stat_text">Performance</span></div></button>
							</div>
								
							</div>
						</div>
						<hr class="hr-color">

						<!-- start tab -->
						<div class="modal-body">
							<div class="tab-content">
								<!-- start general infor -->
								<div class="row">
									<div class="row">
										<div class="col-md-6">
											<?php 
											$work_center_name = isset($work_center) ? $work_center->work_center_name : '';
											$work_center_code = isset($work_center) ? $work_center->work_center_code : '';
											$time_efficiency = isset($work_center) ? $work_center->time_efficiency : 100;
											$capacity = isset($work_center) ? $work_center->capacity : 1;
											$costs_hour = isset($work_center) ? $work_center->costs_hour : 0;
											$oee_target = isset($work_center) ? $work_center->oee_target : 90;
											$time_start = isset($work_center) ? $work_center->time_start : 0;
											$time_stop = isset($work_center) ? $work_center->time_stop : 0;
											$description = isset($work_center) ? $work_center->description : '';
											$working_hour_selected = isset($work_center) ? $work_center->working_hours : '';
											?>

											<?php echo render_input('work_center_name','work_center_name',$work_center_name,'text'); ?>   
										</div>
										<div class="col-md-6">
											<?php echo render_input('work_center_code','work_center_code',$work_center_code,'text'); ?>   
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<?php echo render_select('working_hours',$working_hours,array('id','working_hour_name'),'work_center_working_hours',$working_hour_selected); ?>

										</div>
									</div>
								</div>

								<?php
								/* ----------  prepare machine array for edit vs add --------- */
								if (!empty($machines)) {
								    foreach ($machines as $m) {
								        $machine_list[] = [
								            'machine_name'        => $m->name,
								            'machine_code'	      => $m->code,
								            'operator_id'         => $m->operator_id,
								            'capacity'            => $m->capacity_per_hour,
								            'oee_target'          => $m->oee_target,
								            'costs_hour'          => $m->cost_per_hour,
								            'status'              => $m->status,
								        ];
								    }
								} else {
								    // default row for "Add Work Center"
								    $machine_list[] = [
								        'machine_name'        => '',
								        'machine_code'	      => '',
								        'operator_id'         => '',
								        'capacity'            => 1,
								        'oee_target'          => 90,
								        'costs_hour'          => 0,
								        'status'              => 'active',
								    ];
								}
								?>
								<div class="row">
									<h5 class="h5-color"><?php echo _l('work_center_info'); ?></h5>
									<hr class="hr-color">
									<div class="machine-wrapper">

								        <?php foreach ($machine_list as $idx => $m) { ?>
								        <div class="machine-row panel_s p15 m-b-10">
								            <div class="row">
								                <div class="col-md-6">
								                    <?php echo render_input('machine_name[]','machine_name',$m['machine_name']); ?>
								                </div>
								                <div class="col-md-6">
								                    <?php echo render_input('machine_code[]','machine_code',$m['machine_code']); ?>
								                </div>
								                <div class="col-md-6">
								                    <?php echo render_input('costs_hour[]','costs_hour',$m['costs_hour'],'number'); ?>
								                </div>
								                <div class="col-md-6">
								                    <?php echo render_input('capacity[]','work_center_capacity',$m['capacity'],'number'); ?>
								                </div>
								                <div class="col-md-6">
								                    <?php echo render_input('oee_target[]','oee_target',$m['oee_target'],'number'); ?>
								                </div>
								               <div class="col-md-6">
													<?php echo render_select('staff_id[]', $staffs, array('staffid', array('firstname', 'lastname')), 'Assigned Operator', $m['operator_id'], [], [], '', '', false); ?>
												</div>
								           		<div class="col-md-6">
												  <label for="status"><?php echo _l('status'); ?></label>
												  <select name="status[]" class="form-control selectpicker">
												    <option value="active" <?php echo (isset($m['status']) && $m['status'] == 'active') ? 'selected' : ''; ?>><?php echo _l('active'); ?></option>
												    <option value="idle" <?php echo (isset($m['status']) && $m['status'] == 'idle') ? 'selected' : ''; ?>><?php echo _l('idle'); ?></option>
												    <option value="maintenance" <?php echo (isset($m['status']) && $m['status'] == 'maintenance') ? 'selected' : ''; ?>><?php echo _l('maintenance'); ?></option>
												    <option value="offline" <?php echo (isset($m['status']) && $m['status'] == 'offline') ? 'selected' : ''; ?>><?php echo _l('offline'); ?></option>
												  </select>
												</div>

								                <!-- action buttons -->
								                <div class="col-md-12 text-right mtop15">
								                    <?php if ($idx == 0) { ?>
								                        <button type="button" class="btn btn-success btn-sm add-machine"><i class="fa fa-plus"></i></button>
								                    <?php } else { ?>
								                        <button type="button" class="btn btn-danger btn-sm remove-machine"><i class="fa fa-times"></i></button>
								                    <?php } ?>
								                </div>
								            </div>
								        </div>
								        <?php } ?>

								    </div><!-- /.machine-wrapper -->
									<div class="row">
										<div class="col-md-12">
						                    <?php echo render_input('time_efficiency','time_efficiency',$time_efficiency,'number'); ?>
						                </div>
										<div class="col-md-12">
						                    <?php echo render_input('time_start','work_center_time_start',$time_start,'number'); ?>
						                </div>
						                <div class="col-md-12">
						                    <?php echo render_input('time_stop','time_stop',$time_stop,'number'); ?>
						                </div>
										<div class="col-md-12">
											<p class="bold"><?php echo _l('work_center_description'); ?></p>
											<?php echo render_textarea('description','',$description,array(),array(),'','tinymce'); ?>
										</div>
									</div>
								</div>

							</div>

							<div class="modal-footer">
								<a href="<?php echo admin_url('manufacturing/work_center_manage'); ?>"  class="btn btn-default mr-2 "><?php echo _l('hr_close'); ?></a>
								<?php if(has_permission('manufacturing', '', 'create')&&has_permission('work_centers', '', 'create') || has_permission('manufacturing', '', 'edit') && has_permission('work_centers', '', 'edit')){ ?>
									<button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>

								<?php } ?>
							</div>

						</div>
					</div>
				</div>

				<?php echo form_close(); ?>
			</div>
		</div>
		<?php init_tail(); ?>
		<?php 
		require('modules/manufacturing/assets/js/work_centers/add_edit_work_center_js.php');
		?>
	</body>
	</html>
