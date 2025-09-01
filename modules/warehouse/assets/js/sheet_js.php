<script> 

var sheet_type_value = {};
    function new_sheet_type(){
      "use strict";
      
        $('#sheet_type').modal('show');
        $('.edit-title').addClass('hide');
        $('.add-title').removeClass('hide');
        $('#sheet_type_id').html('');

        var handsontable_html ='<div id="hot_sheet_type" class="hot handsontable htColumnHeaders"></div>';
        if($('#add_handsontable').html() != null){
          $('#add_handsontable').empty();

          $('#add_handsontable').html(handsontable_html);
        }else{
          $('#add_handsontable').html(handsontable_html);

        }

      setTimeout(function(){
        "use strict";
        var hotElement1 = document.querySelector('#hot_sheet_type');


         var sheet_type = new Handsontable(hotElement1, {
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
          autoColumnsheet: {
            samplingRatio: 23
          },

          licenseKey: 'non-commercial-and-evaluation',
          filters: true,
          manualRowResheet: true,
          manualColumnResheet: true,
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
                        data: 'sheet_type_id'
                      },
                      {
                        type: 'text',
                        data: 'sheet_code'
                      },
                       {
                        type: 'text',
                        data: 'sheet_name',
                        // set desired format pattern and
                      },
                       
                    ],

          colHeaders: true,
          nestedHeaders: [{
                            "1":"<?php echo _l('sheet_type_id') ?>",
                            "2":"<?php echo _l('sheet_code') ?>",
                            "3":"<?php echo _l('sheet_name') ?>",
                           
                          }],

          data: [
          {"sheet_code":"","sheet_name":""},
          {"sheet_code":"","sheet_name":""},
          {"sheet_code":"","sheet_name":""},
          {"sheet_code":"","sheet_name":""},
          {"sheet_code":"","sheet_name":""},
          {"sheet_code":"","sheet_name":""},
          {"sheet_code":"","sheet_name":""},
          {"sheet_code":"","sheet_name":""},
          {"sheet_code":"","sheet_name":""},
          ],

        });
         sheet_type_value = sheet_type;
        },300);


    }

  function edit_sheet_type(invoker,id){
    
    "use strict";

    var sheet_code = $(invoker).data('sheet_code');
    var sheet_name = $(invoker).data('sheet_name');

    var order = $(invoker).data('order');
    if($(invoker).data('display') == 0){
      var display = 'no';
    }else{
      var display = 'yes';
    }
    var note = $(invoker).data('note');

        $('#sheet_type_id').html('');
        $('#sheet_type_id').append(hidden_input('id',id));
        $('#sheet_type').modal('show');
        $('.edit-title').removeClass('hide');
        $('.add-title').addClass('hide');

        var handsontable_html ='<div id="hot_sheet_type" class="hot handsontable htColumnHeaders"></div>';
        if($('#add_handsontable').html() != null){
          $('#add_handsontable').empty();

          $('#add_handsontable').html(handsontable_html);
        }else{
          $('#add_handsontable').html(handsontable_html);

        }

    setTimeout(function(){
       "use strict";
      var hotElement1 = document.querySelector('#hot_sheet_type');

       var sheet_type = new Handsontable(hotElement1, {
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
        autoColumnsheet: {
          samplingRatio: 23
        },
        licenseKey: 'non-commercial-and-evaluation',
        filters: true,
        manualRowResheet: true,
        manualColumnResheet: true,
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
                  data: 'sheet_type_id'
                },
                {
                  type: 'text',
                  data: 'sheet_code',
                  readOnly:true,
                  
                },
                 {
                  type: 'text',
                  data: 'sheet_name',
                  // set desired format pattern and
                },
                 
              ],

        colHeaders: true,
        nestedHeaders: [{
                      "1":"<?php echo _l('sheet_type_id') ?>",
                      "2":"<?php echo _l('sheet_code') ?>",
                      "3":"<?php echo _l('sheet_name') ?>",
                    
                    }],

        data: [{"sheet_type_id":id,"sheet_code":sheet_code,"sheet_name":sheet_name}],

      });
       sheet_type_value = sheet_type;
      },300);

    }
    

    function add_sheet_type(invoker){
        "use strict";
        var valid_sheet_type = $('#hot_sheet_type').find('.htInvalid').html();

        if(valid_sheet_type){
          alert_float('danger', "<?php echo _l('data_must_number') ; ?>");
        }else{

          $('input[name="hot_sheet_type"]').val(JSON.stringify(sheet_type_value.getData()));
          $('#add_sheet_type').submit(); 

        }
        
    }
</script>