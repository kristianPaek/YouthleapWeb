<script type="text/javascript">
$(function () {
	var $form = $('#form').validate($.extend({
		rules : {
			login_id: {
				required: true
			},
			answer0: {
				required: true
			},
			answer1: {
				required: true
			},
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
			login_id: {
				required: "Input your ID."
			},
			answer0: {
				required: "Answer to the question(s)."
			},
			answer1: {
				required: "Answer to the question(s)."
			},
			new_password: {
				required: "Input your password.",
				pwd_strength: "Must contain digit,letter and special character.",
				minlength: "Input password more than <?php p(PASSWORD_MIN_LENGTH); ?>letters."
			},
			confirm_new_password: {
				equalTo: "Confirm password."
			}
		}
	}, getValidationRules()));

	$('#form').ajaxForm({
		dataType : 'json',
		success: function(ret, statusText, xhr, form) {
			try {
				if (ret.err_code == 0 && ret.reseted)
				{
					alertBox("Reset success.", "The password has been successfully reset. After a while, we will move to the subscription screen.", function() {
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
        $('.title').html('Security Question in (' + (index + 1) + '<small>/' + total + "</small> steps)");
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
    	confirmBox("Confirm Request", "Do you really want to apply?", function() {
        	$('#form').submit();
		});
    });

    $('#btn_step1').click( function() {
    	if (!$('#form').valid())
    		return false;

    	App.callAPI("api/login/check_pwd_questions", {
	        login_id: $('#login_id').val()
	    }).done(function(res) {
	        if (res.err_code == 0) {
	            if(!res.is_exist) {
					errorBox("Error", "Sorry. This identifier is an unregistered identifier.");
	            }
	            else if (res.qas.length == 0) {
					errorBox("Error", "Sorry. This identifier does not have a password recovery question set.");
	            }
	            else {
	            	$('#question_id0').val(res.qas[0].question_id);
	            	$('#question0').text(res.qas[0].question);
	            	if (res.qas.length > 1) {
		            	$('#question_id1').val(res.qas[1].question_id);
		            	$('#question1').text(res.qas[1].question);	
	            	}

	            	$(".form-wizard").find('li:has([data-toggle="tab"]):eq(' + 1 + ") a").tab("show");

	            	handleTitle(null, $('.steps'), 1);
	            }
	        }
	    });
    });

    $('#btn_step2').click( function() {
    	if (!$('#form').valid())
    		return false;

    	App.callAPI("api/login/check_answer", {
	        login_id: $('#login_id').val(),
	        question_id: $('#question_id0').val(),
	        answer: $('#answer0').val(),
	    }).done(function(res) {
	        if (res.err_code == 0) {
	            if(!res.is_exist) {
					errorBox("Error", "Sorry. This identifier is an unregistered identifier.");
	            }
	            else if (res.checked == false) {
					errorBox("Error", "Sorry. Answer is not correct. Please input again.");
	            }
	            else {
	            	next = 2;
	            	if ($('#question_id1').val() == "")
	            		next = 3;
	            	$(".form-wizard").find('li:has([data-toggle="tab"]):eq(' + next + ") a").tab("show");

	            	handleTitle(null, $('.steps'), next);
	            }
	        }
	    });
    });

    $('#btn_step3').click( function() {
    	if (!$('#form').valid())
    		return false;

    	App.callAPI("api/login/check_answer", {
	        login_id: $('#login_id').val(),
	        question_id: $('#question_id1').val(),
	        answer: $('#answer1').val(),
	    }).done(function(res) {
	        if (res.err_code == 0) {
	            if(!res.is_exist) {
					errorBox("Error", "Sorry. This identifier is an unregistered identifier.");
	            }
	            else if (res.checked == false) {
					errorBox("Error", "Sorry. Answer is not correct. Please input again.");
	            }
	            else {
	            	$(".form-wizard").find('li:has([data-toggle="tab"]):eq(' + 3 + ") a").tab("show");

	            	handleTitle(null, $('.steps'), 3);
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