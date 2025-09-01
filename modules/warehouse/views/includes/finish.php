<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div>
<div class="_buttons">
    <?php if (has_permission('wh_setting', '', 'create') || is_admin() ) { ?>

    <a href="#" onclick="new_finish_type(); return false;" class="btn btn-info pull-left display-block">
        <?php echo _l('add_finish_type'); ?>
    </a>
<?php } ?>

</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<div class="clearfix"></div>
<table class="table dt-table border table-striped">
 <thead>
    <th><?php echo _l('_order'); ?></th>
    <th><?php echo _l('finish_code'); ?></th>
    <th><?php echo _l('finish_name'); ?></th>
        <th><?php echo _l('options'); ?></th>

  
 </thead>
  <tbody>
  <?php foreach($finish_types as $finish_type){ ?>
    <?php if (!empty(trim($finish_type['finish_code'])) && !empty(trim($finish_type['finish_name']))) { ?>
      <tr>
          <td><?php echo _l($finish_type['finish_type_id']); ?></td>
          <td><?php echo _l($finish_type['finish_code']); ?></td>
          <td><?php echo _l($finish_type['finish_name']); ?></td>

          <td>
              <?php if (has_permission('wh_setting', '', 'edit') || is_admin()) { ?>
                <a href="#"
                  onclick="edit_finish_type(this,<?php echo new_html_entity_decode($finish_type['finish_type_id']); ?>); return false;" 
                  data-finish_code="<?php echo new_html_entity_decode($finish_type['finish_code']); ?>" 
                  data-finish_name="<?php echo new_html_entity_decode($finish_type['finish_name']); ?>" 
  
                  class="btn btn-default btn-icon">
                  <i class="fa-regular fa-pen-to-square"></i>
                </a>
              <?php } ?>

              <?php if (has_permission('wh_setting', '', 'delete') || is_admin()) { ?> 
              <a href="<?php echo admin_url('warehouse/delete_finish_type/'.$finish_type['finish_type_id']); ?>" class="btn btn-danger btn-icon _delete">
                <i class="fa fa-remove"></i>
              </a>
              <?php } ?>
          </td>
      </tr>
    <?php } ?>
  <?php } ?>
</tbody>

</table>   

<div class="modal1 fade" id="finish_type" tabindex="-1" role="dialog">
        <div class="modal-dialog setting-handsome-table">
          <?php echo form_open_multipart(admin_url('warehouse/finish_type'), array('id'=>'add_finish_type')); ?>

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <span class="add-title"><?php echo _l('add_finish_type'); ?></span>
                        <span class="edit-title"><?php echo _l('edit_finish_type'); ?></span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                             <div id="finish_type_id">
                             </div>   
                         <div class="form"> 
                            <div class="col-md-12" id="add_handsontable">

                            
                            </div>
                              <?php echo form_hidden('hot_finish_type'); ?>
                        </div>
                        </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                        
                         <button id="latch_assessor" type="button" class="btn btn-info intext-btn" onclick="add_finish_type(this); return false;" ><?php echo _l('submit'); ?></button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
</div>

<?php require 'modules/warehouse/assets/js/finish_js.php';?>
</body>
</html>
