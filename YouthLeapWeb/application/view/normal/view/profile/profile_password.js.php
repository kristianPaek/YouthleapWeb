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
            		document.location = "<?php p($this->_forward_url); ?>";
							});
            });
		}, 
		function() {
			document.location = "<?php p($this->_forward_url); ?>";
		});

	var $password_form = $('#password_form').validate($.extend({
		rules : {
			new_password: {
				required: true
			},
			confirm_new_password: {
				equalTo: $('#new_password')
			}
		},

		// Messages for form validation
		messages : {
			new_password : {
				required : 'Please enter new password.'
			},
			confirm_new_password: {
				equalTo : 'Reenter confirm password.'
			}
		}
	}, getValidationRules()));

	$('#password_form').ajaxForm({
		dataType : 'json',
		success: function(ret, statusText, xhr, form) {
			try {
				if (ret.err_code == 0)
				{	
					alertBox("Success", "Password Changed", function() {
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