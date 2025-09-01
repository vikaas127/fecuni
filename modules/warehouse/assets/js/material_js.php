<script> 

var material_type_value = {};
    function new_material_type(){
      "use strict";
      
        $('#material_type').modal('show');
        $('.edit-title').addClass('hide');
        $('.add-title').removeClass('hide');
        $('#material_type_id').html('');

        var handsontable_html ='<div id="hot_material_type" class="hot handsontable htColumnHeaders"></div>';
        if($('#add_handsontable').html() != null){
          $('#add_handsontable').empty();

          $('#add_handsontable').html(handsontable_html);
        }else{
          $('#add_handsontable').html(handsontable_html);

        }

      setTimeout(function(){
        "use strict";
        var hotElement1 = document.querySelector('#hot_material_type');


         var material_type = new Handsontable(hotElement1, {
          contextMenu: true,
          manualRowMove: true,
          manualColumnMove: true,
          stretchH: 'all',
          autoWrapRow: true,
          rowHeights: 30,
          defaultRowHeight: 100,
          maxRows: 22,
          minRows:9,
          width: '100%',
          height: 330,
          rowHeaders: true,
          autoColumnmaterial: {
            samplingRatio: 23
          },

          licenseKey: 'non-commercial-and-evaluation',
          filters: true,
          manualRowRematerial: true,
          manualColumnRematerial: true,
          allowInsertRow: true,
          allowRemoveRow: true,
          columnHeaderHeight: 40,

          colWidths: [40, 40, 100, 30,30, 30, 140],
          rowHeights: 30,
          // colWidths: 55,
          rowHeaderWidth: [44],
          hiddenColumns: {
            columns: [0],
            indicators: true
          },

          columns: [
                      {
                        type: 'text',
                        data: 'material_type_id'
                      },
                      {
                        type: 'text',
                        data: 'material_code'
                      },
                       {
                        type: 'text',
                        data: 'material_name',
                        // set desired format pattern and
                      },
                       
                    ],

          colHeaders: true,
          nestedHeaders: [{
                            "1":"<?php echo _l('material_type_id') ?>",
                            "2":"<?php echo _l('material_code') ?>",
                            "3":"<?php echo _l('material_name') ?>",
                           
                          }],

          data: [
          {"material_code":"","material_name":""},
          {"material_code":"","material_name":""},
          {"material_code":"","material_name":""},
          {"material_code":"","material_name":""},
          {"material_code":"","material_name":""},
          {"material_code":"","material_name":""},
          {"material_code":"","material_name":""},
          {"material_code":"","material_name":""},
          {"material_code":"","material_name":""},
          ],

        });
         material_type_value = material_type;
        },300);


    }

  function edit_material_type(invoker,id){
    
    "use strict";

    var material_code = $(invoker).data('material_code');
    var material_name = $(invoker).data('material_name');

    var order = $(invoker).data('order');
    if($(invoker).data('display') == 0){
      var display = 'no';
    }else{
      var display = 'yes';
    }
    var note = $(invoker).data('note');

        $('#material_type_id').html('');
        $('#material_type_id').append(hidden_input('id',id));
        $('#material_type').modal('show');
        $('.edit-title').removeClass('hide');
        $('.add-title').addClass('hide');

        var handsontable_html ='<div id="hot_material_type" class="hot handsontable htColumnHeaders"></div>';
        if($('#add_handsontable').html() != null){
          $('#add_handsontable').empty();

          $('#add_handsontable').html(handsontable_html);
        }else{
          $('#add_handsontable').html(handsontable_html);

        }

    setTimeout(function(){
       "use strict";
      var hotElement1 = document.querySelector('#hot_material_type');

       var material_type = new Handsontable(hotElement1, {
        contextMenu: true,
        manualRowMove: true,
        manualColumnMove: true,
        stretchH: 'all',
        autoWrapRow: true,
        rowHeights: 30,
        defaultRowHeight: 100,
        maxRows: 1,
        width: '100%',
        height: 130,
        rowHeaders: true,
        autoColumnmaterial: {
          samplingRatio: 23
        },
        licenseKey: 'non-commercial-and-evaluation',
        filters: true,
        manualRowRematerial: true,
        manualColumnRematerial: true,
        columnHeaderHeight: 40,

        colWidths: [40, 40, 100, 30,30, 30, 140],
        rowHeights: 30,
        rowHeaderWidth: [44],
        hiddenColumns: {
          columns: [0],
          indicators: true
        },


        columns: [
                {
                  type: 'text',
                  data: 'material_type_id'
                },
                {
                  type: 'text',
                  data: 'material_code',
                  readOnly:true,
                  
                },
                 {
                  type: 'text',
                  data: 'material_name',
                  // set desired format pattern and
                },
                 
              ],

        colHeaders: true,
        nestedHeaders: [{
                      "1":"<?php echo _l('material_type_id') ?>",
                      "2":"<?php echo _l('material_code') ?>",
                      "3":"<?php echo _l('material_name') ?>",
                    
                    }],

        data: [{"material_type_id":id,"material_code":material_code,"material_name":material_name}],

      });
       material_type_value = material_type;
      },300);

    }
    

    function add_material_type(invoker){
        "use strict";
        var valid_material_type = $('#hot_material_type').find('.htInvalid').html();

        if(valid_material_type){
          alert_float('danger', "<?php echo _l('data_must_number') ; ?>");
        }else{

          $('input[name="hot_material_type"]').val(JSON.stringify(material_type_value.getData()));
          $('#add_material_type').submit(); 

        }
        
    }
</script>