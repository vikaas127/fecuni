<script>
  // ----------------------
// 🔘 Advanced Filter Integration
// ----------------------

$(document).ready(function () {
  // Load all filter dropdowns when modal opens
  loadFilters();

  // When any filter changes, update the product list
  $('#appointmentModal').on('change', 'select[name="filter_color_id"], select[name="filter_thickness"], select[name="material_type_id"], select[name="finish_type_id"], select[name="profile_type_id"]', function () {
    const color_id    = $('select[name="filter_color_id"]').val();
    const thickness   = $('select[name="filter_thickness"]').val();
    const material_type_id = $('select[name="material_type_id"]').val();
    const finish_type_id   = $('select[name="finish_type_id"]').val();
    const profile_type_id  = $('select[name="profile_type_id"]').val();

    console.log("Sending filters →", { color_id, thickness, material_type_id, finish_type_id, profile_type_id });

    $.post(admin_url + 'manufacturing/filter_products_by_attributes', {
      color_id: color_id,
      thickness: thickness,
      material_type_id: material_type_id,
      finish_type_id: finish_type_id,
      profile_type_id: profile_type_id
    }, function (response) {
      response = JSON.parse(response);
      populateSelect('product_id', response.products);
    });
  });
});

// 🔧 Reusable select builder
function populateSelect(name, items) {
  const $select = $('select[name="' + name + '"]');
  $select.empty();

  // Add empty option
  $select.append($('<option>', {
    value: '',
    text: '-- Select --'
  }));

  // Add dynamic items
  $.each(items, function (i, item) {
    $select.append($('<option>', {
      value: item.value,
      text: item.label
    }));
  });

  $select.selectpicker('refresh');
}

//  Fetch all filter options from server
function loadFilters() {
  $.get(admin_url + 'manufacturing/load_filter_options', function (data) {
    data = JSON.parse(data);
    populateSelect('filter_color_id', data.colors);
    populateSelect('filter_thickness', data.thicknesses);
    populateSelect('material_type_id', data.materials);
    populateSelect('finish_type_id', data.finishes);
    populateSelect('profile_type_id', data.profiles);
  });
}

</script>
<script>
	
	
	init_selectpicker();
	$(".selectpicker").selectpicker('refresh');

	appValidateForm($("body").find('#add_bill_of_material'), {
		'product_id': 'required',
		'routing_id': 'required',
		'bom_code': 'required',
	});  


	$('input[name="bom_type"]').on('click', function() {
	"use strict";

		var bom_type =$(this).val();
		if(bom_type == 'manufacture_this_product'){
			$('.kit_hide').addClass('hide');
		}else if(bom_type == 'kit'){
			$('.kit_hide').removeClass('hide');

		}
	});

	$('select[name="product_id"]').on('change', function () {
    "use strict";

    var product_id = $(this).val();
    console.log("Product selected:", product_id); 

    // Get product variants
    $.get(admin_url + 'manufacturing/get_product_variants/' + product_id, function (response) {
        console.log("Variants response:", response); 

        $("select[name='product_variant_id']").html('');
        $("select[name='product_variant_id']").append(response.product_variants);
        $("select[name='product_variant_id']").selectpicker('refresh');

        $("select[name='unit_id']").val(response.unit_id).selectpicker('refresh');
    }, 'json');
	$.get(admin_url + 'manufacturing/get_detail_by_product/' + product_id, function(response) {
		console.log("Full product object:", response); 

	

	}, 'json');

	});


</script>