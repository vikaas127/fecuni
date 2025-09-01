<script> 

var profile_type_value = {};
    function new_profile_type(){
      "use strict";
      
        $('#profile_type').modal('show');
        $('.edit-title').addClass('hide');
        $('.add-title').removeClass('hide');
        $('#profile_type_id').html('');

        var handsontable_html ='<div id="hot_profile_type" class="hot handsontable htColumnHeaders"></div>';
        if($('#add_handsontable').html() != null){
          $('#add_handsontable').empty();

          $('#add_handsontable').html(handsontable_html);
        }else{
          $('#add_handsontable').html(handsontable_html);

        }

      setTimeout(function(){
        "use strict";
        var hotElement1 = document.querySelector('#hot_profile_type');


         var profile_type = new Handsontable(hotElement1, {
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
          autoColumnprofile: {
            samplingRatio: 23
          },

          licenseKey: 'non-commercial-and-evaluation',
          filters: true,
          manualRowReprofile: true,
          manualColumnReprofile: true,
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
                        data: 'profile_type_id'
                      },
                      {
                        type: 'text',
                        data: 'profile_code'
                      },
                       {
                        type: 'text',
                        data: 'profile_name',
                        // set desired format pattern and
                      },
                       
                    ],

          colHeaders: true,
          nestedHeaders: [{
                            "1":"<?php echo _l('profile_type_id') ?>",
                            "2":"<?php echo _l('profile_code') ?>",
                            "3":"<?php echo _l('profile_name') ?>",
                           
                          }],

          data: [
          {"profile_code":"","profile_name":""},
          {"profile_code":"","profile_name":""},
          {"profile_code":"","profile_name":""},
          {"profile_code":"","profile_name":""},
          {"profile_code":"","profile_name":""},
          {"profile_code":"","profile_name":""},
          {"profile_code":"","profile_name":""},
          {"profile_code":"","profile_name":""},
          {"profile_code":"","profile_name":""},
          ],

        });
         profile_type_value = profile_type;
        },300);


    }

  function edit_profile_type(invoker,id){
    
    "use strict";

    var profile_code = $(invoker).data('profile_code');
    var profile_name = $(invoker).data('profile_name');

    var order = $(invoker).data('order');
    if($(invoker).data('display') == 0){
      var display = 'no';
    }else{
      var display = 'yes';
    }
    var note = $(invoker).data('note');

        $('#profile_type_id').html('');
        $('#profile_type_id').append(hidden_input('id',id));
        $('#profile_type').modal('show');
        $('.edit-title').removeClass('hide');
        $('.add-title').addClass('hide');

        var handsontable_html ='<div id="hot_profile_type" class="hot handsontable htColumnHeaders"></div>';
        if($('#add_handsontable').html() != null){
          $('#add_handsontable').empty();

          $('#add_handsontable').html(handsontable_html);
        }else{
          $('#add_handsontable').html(handsontable_html);

        }

    setTimeout(function(){
       "use strict";
      var hotElement1 = document.querySelector('#hot_profile_type');

       var profile_type = new Handsontable(hotElement1, {
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
        autoColumnprofile: {
          samplingRatio: 23
        },
        licenseKey: 'non-commercial-and-evaluation',
        filters: true,
        manualRowReprofile: true,
        manualColumnReprofile: true,
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
                  data: 'profile_type_id'
                },
                {
                  type: 'text',
                  data: 'profile_code',
                  readOnly:true,
                  
                },
                 {
                  type: 'text',
                  data: 'profile_name',
                  // set desired format pattern and
                },
                 
              ],

        colHeaders: true,
        nestedHeaders: [{
                      "1":"<?php echo _l('profile_type_id') ?>",
                      "2":"<?php echo _l('profile_code') ?>",
                      "3":"<?php echo _l('profile_name') ?>",
                    
                    }],

        data: [{"profile_type_id":id,"profile_code":profile_code,"profile_name":profile_name}],

      });
       profile_type_value = profile_type;
      },300);

    }
    

    function add_profile_type(invoker){
        "use strict";
        var valid_profile_type = $('#hot_profile_type').find('.htInvalid').html();

        if(valid_profile_type){
          alert_float('danger', "<?php echo _l('data_must_number') ; ?>");
        }else{

          $('input[name="hot_profile_type"]').val(JSON.stringify(profile_type_value.getData()));
          $('#add_profile_type').submit(); 

        }
        
    }
</script>