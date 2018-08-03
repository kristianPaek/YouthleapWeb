<script type="text/javascript">

$(function () {
	refresh_class();
	refresh_subject();

	var $form_common = $('#form_common').validate($.extend({
		rules : {
			dob: {
				required: true
			},
			class_list: {
				required: true
			}
		},

		// Messages for form validation
		messages : {
			dob : {
				required : 'Please insert date of birthday.'
			},
			class_list : {
				required : 'Please select Classes.'
			}
		}
	}, getValidationRules()));

	$('#form_common').ajaxForm({
		dataType : 'json',
		success: function(ret, statusText, xhr, form) {
			try {
				if (ret.err_code == 0)
				{	
					alertBox("Success", "Personal info is updated.", function() {
						goto_url("<?php p(_url("tutor/index/1")); ?>");
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

	$('#user_avatar').change(function(e) {
		$("#avatar_url").val(e.target.value);
	});
  
  $('.btn-active').click(function() {
      var tutor_id = "<?php p($mTutor->id); ?>";
      var tutor_name = "<?php p($mTutor->first_name . " " . $mTutor->last_name); ?>";
			var user_token = "<?php p(_token());?>";
      confirmBox("Tutor Active", "Do you want to active "+tutor_name+"?", function(note) {
        App.callAPI("api/tutor/active",
          {
            tutor_id: tutor_id,
            is_active: 1,
						user_token: user_token
          })
        .done(function(res) {
                alertBox("Tutor Active", tutor_name+" actived successfully", function() {
									goto_url("<?php p(_url("tutor/index/1")); ?>");
                });
              })
              .fail(function(res) {
                errorBox("Active Error", res.err_msg);
              });
      });
    });
  
  $('.btn-inactive').click(function() {
      var tutor_id = "<?php p($mTutor->id); ?>";
      var tutor_name = "<?php p($mTutor->first_name . " " . $mTutor->last_name); ?>";
			var user_token = "<?php p(_token());?>";
      confirmBox("Tutor Inactive", "Do you want to inactive "+tutor_name+"?", function(note) {
        App.callAPI("api/tutor/active",
          {
            tutor_id: tutor_id,
            is_active: 0,
						user_token: user_token
          })
        .done(function(res) {
                alertBox("Tutor Inactive", tutor_name+" inactived successfully", function() {
									goto_url("<?php p(_url("tutor/index/1")); ?>");
                });
              })
              .fail(function(res) {
                errorBox("Inactive Error", res.err_msg);
              });
      });
		});
		
  $('.btn-remove').click(function() {
      var tutor_id = "<?php p($mTutor->id); ?>";
      var tutor_name = "<?php p($mTutor->first_name . " " . $mTutor->last_name); ?>";
			var user_token = "<?php p(_token());?>";
      confirmBox("Tutor Remove", "Do you want to remove "+tutor_name+"?", function(note) {
        App.callAPI("api/tutor/remove",
          {
            tutor_id: tutor_id,
						user_token: user_token
          })
        .done(function(res) {
                alertBox("Tutor Remove", tutor_name+" removed successfully", function() {
									goto_url("<?php p(_url("tutor/index/1")); ?>");
                });
              })
              .fail(function(res) {
                errorBox("Remove Error", res.err_msg);
              });
      });
    });
});

function on_select_class(classes)
{
	v = "";
	for(i = 0; i < classes.length; i ++) {
		if (v != "")
			v += ";";

		v += classes[i].class_id + ":" + classes[i].class_name;
	}

	$('#classes').val(v);

	refresh_class();
}

function refresh_class()
{
	var lks = "";
	var classes = $('#classes').val();
  var class_ids = "";
  var select_url = "<?php p(_url("subclass/multi_select//0/"));?>";

	if (classes != "")
	{
		var ps = classes.split(';');
		for (i = 0; i < ps.length; i ++)
		{
			var fs = ps[i].split(':');
			lks += "<span>" + fs[1] + " <a href='javascript:;' class='remove-class' no=" + i + "><i class='ln-icon-cross2'></i></a>";
			lks += " </span>";

			select_url += fs[0] + "/";

			if (class_ids != "")
				class_ids += ",";
			class_ids += fs[0];
		}
		$('#class_list').html(lks);

		$('.remove-class').click(function() {
			var no = $(this).attr('no');
			var classes = $('#classes').val();
			var new_class = "";
			if (classes != "")
			{
				var ps = classes.split(';');
				for (i = 0; i < ps.length; i ++)
				{
					if (i != no)
					{
						if (new_class != "")
							new_class += ";";
						new_class += ps[i];
					}
				}
			}
			$('#classes').val(new_class);
			refresh_class();
		});
	}
	else {
		$('#class_list').html(lks);

	}
	select_url += "?callback=on_select_class";
	$('#class_select').attr("href", select_url);

	// select_url = "<?php p(_url('pattr/select/')); ?>" + class_ids + "?callback=on_insert_attribute";
	// $('#btn_insert_attribute').attr("href", select_url);
}

function on_select_subject(subjects)
{
	v = "";
	for(i = 0; i < subjects.length; i ++) {
		if (v != "")
			v += ";";

		v += subjects[i].subject_id + ":" + subjects[i].subject_name;
	}

	$('#subjects').val(v);

	refresh_subject();
}

function refresh_subject()
{
	var lks = "";
	var subjects = $('#subjects').val();
  var subject_ids = "";
  var select_url = "<?php p(_url("subsubject/multi_select//"));?>";

	if (subjects != "")
	{
		var ps = subjects.split(';');
		for (i = 0; i < ps.length; i ++)
		{
			var fs = ps[i].split(':');
			lks += "<span>" + fs[1] + " <a href='javascript:;' class='remove-subject' no=" + i + "><i class='ln-icon-cross2'></i></a>";
			lks += " </span>";

			select_url += fs[0] + "/";

			if (subject_ids != "")
				subject_ids += ",";
			subject_ids += fs[0];
		}
		$('#subject_list').html(lks);

		$('.remove-subject').click(function() {
			var no = $(this).attr('no');
			var subjects = $('#subjects').val();
			var new_subject = "";
			if (subjects != "")
			{
				var ps = subjects.split(';');
				for (i = 0; i < ps.length; i ++)
				{
					if (i != no)
					{
						if (new_class != "")
							new_class += ";";
						new_class += ps[i];
					}
				}
			}
			$('#subjects').val(new_class);
			refresh_subject();
		});
	}
	else {
		$('#subject_list').html(lks);

	}
	select_url += "?callback=on_select_subject";
	$('#subject_select').attr("href", select_url);

	// select_url = "<?php p(_url('pattr/select/')); ?>" + class_ids + "?callback=on_insert_attribute";
	// $('#btn_insert_attribute').attr("href", select_url);
}
</script>