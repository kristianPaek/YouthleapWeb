<script type="text/javascript">
$(function () {
	confirmPasswordBox("Confirm Password", "You have to confirm current password to update your password. Please enter current password.", 
		function(password) {
			params = {
				password: password,
				user_token: "<?php p(_token());?>"
			};
            App.callAPI("api/profile/check_password", params).done(function(res) {
				$('[name="old_password"]').val(password);
            }).fail(function(res) {
            	errorBox("Error", res.err_msg, function() {
								// document.location = "<?php p($this->_forward_url); ?>";
								goto_url("/home");
							});
            });
		}, 
		function() {
			document.location = "<?php p($this->_forward_url); ?>";
		});

	var $myinfo_form = $('#myinfo_form').validate($.extend({
		rules : {
			first_name: {
				required: true
			},
			birthday: {
				required: true
			},
			email: {
				email: true
			},
			sex: {
				required: true
			}
		},

		// Messages for form validation
		messages : {
			first_name: {
				required: "Please enter first name"
			},
			birthday: {
				required: "Please enter birthday."
			},
			email: {
				email: "Invalid email address."
			},
			sex: {
				required: "Please select gender."
			}
		}
	}, getValidationRules()));

	$('#myinfo_form').ajaxForm({
		dataType : 'json',
		success: function(ret, statusText, xhr, form) {
			try {
				if (ret.err_code == 0)
				{	
					alertBox("Success", "Profile updated.", function() {
						document.location = "<?php p($this->_forward_url); ?>";
					});
				}
				else if (ret.err_msg != "")
				{
					errorBox("Error", ret.err_msg);
				}
			}
			finally {
			}
		}
	});

});
</script>