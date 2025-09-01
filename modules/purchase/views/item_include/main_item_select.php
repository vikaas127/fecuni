<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row mbot25">
      <div class="col-md-2">
        <label for="filter_sheet"><?php echo _l('Sheet'); ?></label>
        <select id="filter_sheet" class="selectpicker filter-item" data-width="100%" data-none-selected-text="<?php echo _l('select_sheet'); ?>">
            <option value=""></option>
            <?php foreach($sheets as $sheet) { ?>
                <option value="<?php echo $sheet['sheet_type_id']; ?>"><?php echo $sheet['sheet_name']; ?></option>
            <?php } ?>
        </select>
    </div>
    <!-- Length Filter -->
    <div class="col-md-2">
        <label for="filter_Length"><?php echo _l('Length'); ?></label>
        <select id="filter_Length" class="selectpicker filter-item" data-width="100%" data-none-selected-text="<?php echo _l('select_Length'); ?>">
            <option value=""></option>
            <?php foreach($Lengths as $Length) { ?>
                <option value="<?php echo $Length; ?>"><?php echo $Length; ?></option>
            <?php } ?>
        </select>
    </div>

    <!-- Thickness Filter -->
    <div class="col-md-2">
        <label for="filter_Thickness"><?php echo _l('Thickness'); ?></label>
        <select id="filter_Thickness" class="selectpicker filter-item" data-width="100%" data-none-selected-text="<?php echo _l('select_Thickness'); ?>">
            <option value=""></option>
            <?php foreach($Thicknesses as $thick) { ?>
                <option value="<?php echo $thick; ?>"><?php echo $thick; ?></option>
            <?php } ?>
        </select>
    </div>

    <!-- Color Filter -->
    <div class="col-md-3">
        <label for="filter_color"><?php echo _l('Color'); ?></label>
        <select id="filter_color" class="selectpicker filter-item" data-width="100%" data-none-selected-text="<?php echo _l('select_color'); ?>">
            <option value=""></option>
            <?php foreach($colors as $color) { ?>
                <option value="<?php echo $color['color_id']; ?>"><?php echo $color['color_name']; ?></option>
            <?php } ?>
        </select>
    </div>

    <!-- Finish Type Filter -->
    <div class="col-md-3">
        <label for="filter_finish"><?php echo _l('Finish Type'); ?></label>
        <select id="filter_finish" class="selectpicker filter-item" data-width="100%" data-none-selected-text="<?php echo _l('select_finish'); ?>">
            <option value=""></option>
            <?php foreach($finishes as $finish) { ?>
                <option value="<?php echo $finish['finish_type_id']; ?>"><?php echo $finish['finish_name']; ?></option>
            <?php } ?>
        </select>
    </div>
</div>


<div class="form-group mbot25  select-placeholder col-md-4">
     <select name="item_select" class="selectpicker no-margin<?php if($ajaxItems == true){echo ' ajax-search';} ?>" data-width="100%"  id="item_select" data-none-selected-text="<?php echo _l('select_item'); ?>" data-live-search="true">
      <option value=""></option>
      <?php foreach($items as $group_id=>$_items){ ?>
      <optgroup data-group-id="<?php echo $group_id; ?>" label="<?php echo $_items[0]['group_name']; ?>">
       <?php foreach($_items as $item){ ?>
       <option value="<?php echo $item['id']; ?>" data-subtext="<?php echo strip_tags(mb_substr($item['long_description'] ?? '',0,200)).'...'; ?>">(<?php echo app_format_number($item['purchase_price']); ; ?>) <?php echo $item['description']; ?></option>
       <?php } ?>
     </optgroup>
     <?php } ?>
   </select>
</div>


