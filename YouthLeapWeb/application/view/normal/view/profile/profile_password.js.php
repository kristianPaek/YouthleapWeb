<script type="text/javascript">
$(function () {
	confirmPasswordBox("Confirm Password", "You have to confirm current password to update your password. Please enter current password.", 
		function(password) {
			params = {
				password: password
			};
            App.callAPI("api/profile/check_password", params).done(function(res) {
				$('[name="old_password"]').val(password);
            }).fail(function(res) {
            	alert(res.err_msg);
            	document.location = "<?php p($this->_forward_url); ?>";
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

	var $qas_form = $('#qas_form').validate($.extend({
		rules : {
			question_id0: {
				requireWith: '#answer0'
			},
			answer0: {
				requireWith: '#question_id0'
			},
			question_id1: {
				requireWith: '#answer1',
				notEqualTo: ['#question_id0']
			},
			answer1: {
				requireWith: '#question_id1'
			},
			question_id2: {
				requireWith: '#answer2',
				notEqualTo: ['#question_id0', '#question_id1']
			},
			answer2: {
				requireWith: '#question_id2'
			},
			question_id3: {
				requireWith: '#answer3',
				notEqualTo: ['#question_id0', '#question_id1', '#question_id2']
			},
			answer3: {
				requireWith: '#question_id3'
			}
		},

		// Messages for form validation
		messages : {
			question_id0: {
				requireWith: "Input your question."
			},
			answer0: {
				requireWith: 'Input your answer.'
			},
			question_id1: {
				requireWith: "Input your question.",
				notEqualTo: "Select another question."
			},
			answer1: {
				requireWith: 'Input your answer.'
			},
			question_id2: {
				requireWith: "Input your question.",
				notEqualTo: "Select another question."
			},
			answer2: {
				requireWith: 'Input your answer.'
			},
			question_id3: {
				requireWith: "Input your question.",
				notEqualTo: "Select another question."
			},
			answer3: {
				requireWith: 'Input your answer.'
			}
		}
	}, getValidationRules()));

	$('#qas_form').ajaxForm({
		dataType : 'json',
		success: function(ret, statusText, xhr, form) {
			try {
				if (ret.err_code == 0)
				{	
					alertBox("Confirm", "Question successfuly changed.", function() {
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