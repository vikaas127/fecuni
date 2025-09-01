<script src="https://cdn.jsdelivr.net/npm/jquery-circle-progress@1.2.2/dist/circle-progress.min.js"></script>

<script>
		$(function(){
		'use strict';
		
		appValidateForm($('#add_edit_member'), {
			firstname: 'required',
			lastname: 'required',
			staff_identifi: 'required',
			status_work: 'required',
			job_position: 'required',
			password: {
				required: {
					depends: function(element) {
						return ($('input[name="isedit"]').length == 0) ? true : false
					}
				}
			},
			email: {
				required: true,
				email: true,
				remote: {
					url: site_url + "admin/misc/staff_email_exists",
					type: 'post',
					data: {
						email: function() {
							return $('input[name="email"]').val();
						},
						memberid: function() {
							return $('input[name="memberid"]').val();
						}
					}
				}
			},
			staff_identifi: {
				required: true,
				remote: {
					url: site_url + "admin/hr_profile/hr_code_exists",
					type: 'post',
					data: {
						staff_identifi: function() {
							return $('input[name="staff_identifi"]').val();
						},
						memberid: function() {
							return $('input[name="memberid"]').val();
						}
					}
				}
			}
		});

		init_datepicker();
		init_selectpicker();
		$(".selectpicker").selectpicker('refresh');

		$('select[name="role_v"]').on('change', function() {
			var roleid = $(this).val();
			init_roles_permissions_v2(roleid, true);
		});


		$("input[name='profile_image']").on('change', function() {
			readURL(this);
		});
 const $ifscInput = $("input[name='ifsc_code']");
    const $bank = $("input[name='issue_bank']");

    // Setup wrappers and status/icon elements once
    if (!$ifscInput.parent().hasClass('ifsc-wrapper')) {
        $ifscInput.wrap('<div class="ifsc-wrapper" style="position:relative;"></div>');
        $ifscInput.after(`
            <span id="ifsc-status" style="position:absolute; right:10px; top:50%; transform:translateY(-50%);">
                <img id="ifsc-icon" src="https://cdn-icons-png.flaticon.com/512/845/845646.png" width="18" height="18" style="filter: grayscale(100%);">
            </span>
        `);
        $ifscInput.parent().after('<div id="ifsc-info" style="font-size:13px; color:#666; margin-top:5px;"></div>');
    }
	});
$("input[name='ifsc_code']").on('change', function () {
     const ifsc = $(this).val().trim();
    const $ifscInput = $(this);
    const $status = $("#ifsc-status"); // ✅ Must be inside here
    const $info = $("#ifsc-info");
    const $bank = $("input[name='issue_bank']");

    $status.html('');
    $info.html('');
    $bank.val('');

    if (ifsc.length < 5) return;

   $.ajax({
        url: 'https://ifsc.razorpay.com/' + encodeURIComponent(ifsc),
        method: 'GET',
        success: function (data) {
            // ✅ $status must be in scope
           $status.html(
    '<img src="https://cdn-icons-png.flaticon.com/512/845/845646.png" alt="Verified" width="16" height="16" style="margin-right: 4px; vertical-align:middle;">' +
    '<span style="color:green; font-size:11px;">Verified</span>'
);

            
            $info.html(`${data.BANK}, ${data.CITY} – ${data.BRANCH}`).css('color', '#666');
            $bank.val(data.BANK);
        },
        error: function () {
            $status.html(
                '<img src="https://cdn-icons-png.flaticon.com/512/753/753345.png" alt="Invalid" width="16" height="16" style="margin-right: 4px; vertical-align:middle;">' +
                '<span style="color:red;">Invalid IFSC</span>'
            );
            $info.html('');
        }
    });
});
$('input[name="tax_regime"]').change(function() {
    let regime = $(this).val();
    let employee_id = $('#employee_id').val();

    $.post("<?= admin_url('hr_profile/calculate_tax') ?>", {
        regime: regime,
        employee_id: employee_id
    }, function(res) {
        if (res.error) {
            $('#tax-result').html('<span style="color:red;">' + res.error + '</span>');
        } else {
            $('#tax-result').html('Estimated Tax: ₹' + res.tax);
        }
    }, 'json');
});

	function init_roles_permissions_v2(roleid, user_changed) {
		"use strict";

		roleid = typeof(roleid) == 'undefined' ? $('select[name="role_v"]').val() : roleid;
		var isedit = $('.member > input[name="isedit"]');

    // Check if user is edit view and user has changed the dropdown permission if not only return
    if (isedit.length > 0 && typeof(roleid) !== 'undefined' && typeof(user_changed) == 'undefined') {
    	return;
    }

    // Administrators does not have permissions
    if ($('input[name="administrator"]').prop('checked') === true) {
    	return;
    }

    // Last if the roleid is blank return
    if (roleid === '') {
    	return;
    }

    // Get all permissions
    var permissions = $('table.roles').find('tr');
    requestGetJSON('hr_profile/hr_role_changed/' + roleid).done(function(response) {

    	permissions.find('.capability').not('[data-not-applicable="true"]').prop('checked', false).trigger('change');

    	$.each(permissions, function() {
    		var row = $(this);
    		$.each(response, function(feature, obj) {
    			if (row.data('name') == feature) {
    				$.each(obj, function(i, capability) {
    					row.find('input[id="' + feature + '_' + capability + '"]').prop('checked', true);
    					if (capability == 'view') {
    						row.find('[data-can-view]').change();
    					} else if (capability == 'view_own') {
    						row.find('[data-can-view-own]').change();
    					}
    				});
    			}
    		});
    	});
    });
}

	function readURL(input) {
		"use strict";
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$("img[id='wizardPicturePreview']").attr('src', e.target.result).fadeIn('slow');
			}
			reader.readAsDataURL(input.files[0]);
		}
	}


	$(function(){
		'use strict';
		init_roles_permissions_v1();


		function init_roles_permissions_v1(roleid, user_changed) {
		"use strict";
			
			roleid = typeof(roleid) == 'undefined' ? $('select[name="role_v"]').val() : roleid;
			var isedit = $('.member > input[name="isedit"]');

    // Check if user is edit view and user has changed the dropdown permission if not only return
    if (isedit.length > 0 && typeof(roleid) !== 'undefined' && typeof(user_changed) == 'undefined') {
    	return;
    }

    // Administrators does not have permissions
    if ($('input[name="administrator"]').prop('checked') === true) {
    	return;
    }

    // Last if the roleid is blank return
    if (roleid === '') {
    	return;
    }

    // Get all permissions
    var permissions = $('table.roles').find('tr');
    requestGetJSON('hr_profile/hr_role_changed/' + roleid).done(function(response) {

    	permissions.find('.capability').not('[data-not-applicable="true"]').prop('checked', false).trigger('change');

    	$.each(permissions, function() {
    		var row = $(this);
    		$.each(response, function(feature, obj) {
    			if (row.data('name') == feature) {
    				$.each(obj, function(i, capability) {
    					row.find('input[id="' + feature + '_' + capability + '"]').prop('checked', true);
    					if (capability == 'view') {
    						row.find('[data-can-view]').change();
    					} else if (capability == 'view_own') {
    						row.find('[data-can-view-own]').change();
    					}
    				});
    			}
    		});
    	});
    });
}


	});
	
</script>