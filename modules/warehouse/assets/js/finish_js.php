<script> 

var finish_type_value = {};
    function new_finish_type(){
      "use strict";
      
        $('#finish_type').modal('show');
        $('.edit-title').addClass('hide');
        $('.add-title').removeClass('hide');
        $('#finish_type_id').html('');

        var handsontable_html ='<div id="hot_finish_type" class="hot handsontable htColumnHeaders"></div>';
        if($('#add_handsontable').html() != null){
          $('#add_handsontable').empty();

          $('#add_handsontable').html(handsontable_html);
        }else{
          $('#add_handsontable').html(handsontable_html);

        }

      setTimeout(function(){
        "use strict";
        var hotElement1 = document.querySelector('#hot_finish_type');


         var finish_type = new Handsontable(hotElement1, {
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
          autoColumnfinish: {
            samplingRatio: 23
          },

          licenseKey: 'non-commercial-and-evaluation',
          filters: true,
          manualRowRefinish: true,
          manualColumnRefinish: true,
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
                        data: 'finish_type_id'
                      },
                      {
                        type: 'text',
                        data: 'finish_code'
                      },
                       {
                        type: 'text',
                        data: 'finish_name',
                        // set desired format pattern and
                      },
                       
                    ],

          colHeaders: true,
          nestedHeaders: [{
                            "1":"<?php echo _l('finish_type_id') ?>",
                            "2":"<?php echo _l('finish_code') ?>",
                            "3":"<?php echo _l('finish_name') ?>",
                           
                          }],

          data: [
          {"finish_code":"","finish_name":""},
          {"finish_code":"","finish_name":""},
          {"finish_code":"","finish_name":""},
          {"finish_code":"","finish_name":""},
          {"finish_code":"","finish_name":""},
          {"finish_code":"","finish_name":""},
          {"finish_code":"","finish_name":""},
          {"finish_code":"","finish_name":""},
          {"finish_code":"","finish_name":""},
          ],

        });
         finish_type_value = finish_type;
        },300);


    }

  function edit_finish_type(invoker,id){
    
    "use strict";

    var finish_code = $(invoker).data('finish_code');
    var finish_name = $(invoker).data('finish_name');

    var order = $(invoker).data('order');
    if($(invoker).data('display') == 0){
      var display = 'no';
    }else{
      var display = 'yes';
    }
    var note = $(invoker).data('note');

        $('#finish_type_id').html('');
        $('#finish_type_id').append(hidden_input('id',id));
        $('#finish_type').modal('show');
        $('.edit-title').removeClass('hide');
        $('.add-title').addClass('hide');

        var handsontable_html ='<div id="hot_finish_type" class="hot handsontable htColumnHeaders"></div>';
        if($('#add_handsontable').html() != null){
          $('#add_handsontable').empty();

          $('#add_handsontable').html(handsontable_html);
        }else{
          $('#add_handsontable').html(handsontable_html);

        }

    setTimeout(function(){
       "use strict";
      var hotElement1 = document.querySelector('#hot_finish_type');

       var finish_type = new Handsontable(hotElement1, {
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
        autoColumnfinish: {
          samplingRatio: 23
        },
        licenseKey: 'non-commercial-and-evaluation',
        filters: true,
        manualRowRefinish: true,
        manualColumnRefinish: true,
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
                  data: 'finish_type_id'
                },
                {
                  type: 'text',
                  data: 'finish_code',
                  readOnly:true,
                  
                },
                 {
                  type: 'text',
                  data: 'finish_name',
                  // set desired format pattern and
                },
                 
              ],

        colHeaders: true,
        nestedHeaders: [{
                      "1":"<?php echo _l('finish_type_id') ?>",
                      "2":"<?php echo _l('finish_code') ?>",
                      "3":"<?php echo _l('finish_name') ?>",
                    
                    }],

        data: [{"finish_type_id":id,"finish_code":finish_code,"finish_name":finish_name}],

      });
       finish_type_value = finish_type;
      },300);

    }
    

    function add_finish_type(invoker){
        "use strict";
        var valid_finish_type = $('#hot_finish_type').find('.htInvalid').html();

        if(valid_finish_type){
          alert_float('danger', "<?php echo _l('data_must_number') ; ?>");
        }else{

          $('input[name="hot_finish_type"]').val(JSON.stringify(finish_type_value.getData()));
          $('#add_finish_type').submit(); 

        }
        
    }
</script>