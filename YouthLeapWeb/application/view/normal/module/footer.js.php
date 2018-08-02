<script type="text/javascript">

$(function () {

	<?php if (_user_id() == null) { ?>
		$('#feedback_form').on('submit', function() {
            errorBox("<?php p(STR_NOTICE); ?>", "<?php p(STR_REQUIRED_LOGIN); ?>", function() {
                goto_url("login");
            });
            return false;
		});
	<?php } else { ?>
		var $form = $('#feedback_form').validate($.extend({
			ignore: '',
			rules : {
				content: {
					required: true,
					maxlength: 200
				}
			},

			// Messages for form validation
			messages : {
				content : {
	                required : '<?php p(STR_INPUT_CONTENT); ?>',
	                maxlength: $.validator.format( "Cann't input more than {0} letters." )
				}
			}
		}, getValidationRules()));
			
		$('#feedback_form').ajaxForm({
			dataType : 'json',
			success: function(ret, statusText, xhr, form) {
	            try {
	                if (ret.err_code == 0)
	                {   
	                    alertBox("<?php p(STR_SAVE_SUCCESS); ?>", "<?php p(STR_DESC_OPINION_SUCCESS); ?>", function() {
	                        $('#feedback_form_content').val('');
	                    });
	                }
	                else if (ret.err_msg != "")
	                {
	                    errorBox("<?php p(STR_ERROR_SAVE); ?>", ret.err_msg);
	                }
	            }
	            finally {
	            }
	        }
		});
	<?php } ?>
});

</script>