<div class="row">
  <div class="col-md-12">
    <h4><?php echo _l('Conversion Rate'); ?></h4>
    <hr>

    <table class="table table-bordered" id="conversionTable">
      <thead>
        <tr>
          <th><?php echo _l('Name'); ?></th>
          <th><?php echo _l('Value'); ?></th>
          <th><?php echo _l('Actions'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($conversion_rate)){ 
          foreach($conversion_rate as $row){ ?>
          <tr data-id="<?php echo $row['id']; ?>">
            <td><input type="text" class="form-control p_name" value="<?php echo $row['p_name']; ?>"></td>
            <td><input type="number" step="0.01" class="form-control p_value" value="<?php echo $row['p_value']; ?>"></td>
            <td>
              <button class="btn btn-sm btn-success saveRow">Save</button>
              <button class="btn btn-sm btn-danger deleteRow">Delete</button>
            </td>
          </tr>
        <?php } } ?>
        <!-- Extra empty row for new entry -->
        <tr data-id="">
          <td><input type="text" class="form-control p_name" placeholder="Enter name"></td>
          <td><input type="number" step="0.01" class="form-control p_value" placeholder="Enter value"></td>
          <td><button class="btn btn-sm btn-primary saveRow">Add</button></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <th>Total</th>
          <th id="totalValue">0.00</th>
          <th></th>
        </tr>
      </tfoot>
    </table>
  </div>
</div>


