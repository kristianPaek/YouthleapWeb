<script type="text/javascript">
    $(function () {
        var $form = $('#form').validate($.extend({
            rules : {
                email: {
                    required: true
                },
                password: {
                    required: true
                }
            },

            // Messages for form validation
            messages : {
                email : {
                    required : '<?php l("Please enter email address."); ?>'
                },
                password : {
                    required : '<?php l("Please enter password."); ?>'
                }
            }
        }, getValidationRules()));
    });
</script>