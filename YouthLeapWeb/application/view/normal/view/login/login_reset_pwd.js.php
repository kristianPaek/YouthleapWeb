<script type="text/javascript">
$(function () {
	var $form = $('#form').validate($.extend({
		rules : {
			new_password: {
				required: true,
				pwd_strength: true,
				minlength: <?php p(PASSWORD_MIN_LENGTH); ?>
			},
			confirm_new_password: {
				equalTo: $('#new_password')
			}
		},

		// Messages for form validation
		messages : {
			new_password: {
				required: "Please input password.",
				pwd_strength: "Must contain number,digit and special character",
				minlength: "Must input more than <?php p(PASSWORD_MIN_LENGTH); ?> letters."
			},
			confirm_new_password: {
				equalTo: "Confirm the password."
			}
		}
	}, getValidationRules()));

	$('#form').ajaxForm({
		dataType : 'json',
		success: function(ret, statusText, xhr, form) {
			try {
				if (ret.err_code == 0 && ret.reseted)
				{
					alertBox("Reset finished", "Password reset success. Please wait...", function() {
						goto_url("login");
					});
					return;
				}
				else {
					errorBox("Error", "Sorry. Error occured.");
				}
			}
			finally {
			}
		}
	});
});
</script>