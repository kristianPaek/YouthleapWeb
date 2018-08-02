<script type="text/javascript">
$(function () {
	var $form = $('#form').validate($.extend({
		rules : {
			login_id: {
				required: true
			},
			email: {
				required: true
			}
		},

		// Messages for form validation
		messages : {
			login_id: {
				required: "Input your ID."
			},
			email: {
				required: "Input your e-mail."
			}
		}
	}, getValidationRules()));

	$('#form').ajaxForm({
		dataType : 'json',
		success: function(ret, statusText, xhr, form) {
			try {
				if (ret.err_code == 0 && ret.reseted)
				{
					alertBox("Reset finsihed", "The password has been successfully reset. After a while, we will move to the subscription screen.", function() {
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

	var handleTitle = function(tab, navigation, index) {
		//$(".form-wizard").find('li:has([data-toggle="tab"]):eq(' + index + ") a").tab("show");
        change_title(tab, navigation, index);
        return true;
    }

    var change_title = function(tab, navigation, index) {
        var total = navigation.find('li').length;
        var current = index + 1;

        // set wizard title
        $('.title').html('Email password reset (' + (index + 1) + '<small>/' + total + "</small>steps)");
        // set done steps
        jQuery('li', $('#form')).removeClass("done");
        var li_list = navigation.find('li');
        for (var i = 0; i < index; i++) {
            jQuery(li_list[i]).addClass("done");
        }

        $('#form').find('.button-next').hide();
        $('#form').find('#btn_step' + current).show();

        App.scrollTo($('.title'));
    }

    handleTitle(null, $('.steps'), 0);

    // default form wizard
    $('#form').bootstrapWizard({
        'tabClass': 'steps',
        onTabClick: function (tab, navigation, index, clickedIndex) {
            return false;
        },
        onNext: function (tab, navigation, index) {
            return false;
        },
        onPrevious: function (tab, navigation, index) {
            return false;
        },
        onTabShow: function (tab, navigation, index) {
            
        }
    });

    $('.button-submit').click( function() {
    	confirmBox("Confirm request", "Do you want to apply for your application?", function() {
        	$('#form').submit();
		});
    });

    $('#btn_step1').click( function() {
    	if (!$('#form').valid())
    		return false;

    	App.callAPI("api/login/check_login_id", {
	        login_id: $('#login_id').val()
	    }).done(function(res) {
	        if (res.err_code == 0) {
	            if(!res.is_exist) {
					errorBox("Error", "Sorry. This subscription identifier is an unregistered identifier.");
	            }
	            else {
	            	$(".form-wizard").find('li:has([data-toggle="tab"]):eq(' + 1 + ") a").tab("show");

	            	handleTitle(null, $('.steps'), 1);
	            }
	        }
	    });
    });

    $('#btn_step2').click( function() {
    	if (!$('#form').valid())
    		return false;

    	App.callAPI("api/login/check_email", {
	        login_id: $('#login_id').val(),
	        email: $('#email').val()
	    }).done(function(res) {
	        if (res.err_code == 0) {
	            if(!res.is_exist) {
					errorBox("Error", "Sorry. This subscription identifier is an unregistered identifier.");
	            }
	            else if (res.checked == false) {
					errorBox("Error", "Sorry. Email address is not correct.");
	            }
	            else {
	            	alertBox("Send Email", "You have sent a password reset e-mail to the specified e-mail address. Please check your e-mail.");
	            }
	        }
	    });
    });

    $('#btn_step4').click( function() {
    	if (!$('#form').valid())
    		return false;

    	$('#form').submit();
    });
});
</script>