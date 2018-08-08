<script type="text/javascript">

$(function () {
	
	refresh_class();
	$('#form_common').ajaxForm({
		dataType : 'json',
		success: function(ret, statusText, xhr, form) {
			try {
				if (ret.err_code == 0)
				{	
					alertBox("Success", "Personal info is updated.", function() {
						goto_url("<?php p(_url("student/index/1")); ?>");
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
  
  $('.btn-active').click(function() {
      var student_id = "<?php p($mStudent->id); ?>";
      var student_name = "<?php p($mStudent->first_name . " " . $mStudent->last_name); ?>";
			var user_token = "<?php p(_token());?>";
      confirmBox("Student Active", "Do you want to active "+student_name+"?", function(note) {
        App.callAPI("api/student/active",
          {
            student_id: student_id,
            is_active: 1,
						user_token: user_token
          })
        .done(function(res) {
                alertBox("Student Active", student_name+" actived successfully", function() {
									goto_url("<?php p(_url("student/index/1")); ?>");
                });
              })
              .fail(function(res) {
                errorBox("Active Error", res.err_msg);
              });
      });
    });
  
  $('.btn-inactive').click(function() {
      var student_id = "<?php p($mStudent->id); ?>";
      var student_name = "<?php p($mStudent->first_name . " " . $mStudent->last_name); ?>";
      var user_token = "<?php p(_token());?>";
      confirmBox("Student Inactive", "Do you want to inactive "+student_name+"?", function(note) {
        App.callAPI("api/student/active",
          {
            student_id: student_id,
            is_active: 0,
						user_token: user_token
          })
        .done(function(res) {
                alertBox("Student Inactive", student_name+" inactived successfully", function() {
									goto_url("<?php p(_url("student/index/1")); ?>");
                });
              })
              .fail(function(res) {
                errorBox("Inactive Error", res.err_msg);
              });
      });
		});
		
  $('.btn-remove').click(function() {
      var student_id = "<?php p($mStudent->id); ?>";
      var student_name = "<?php p($mStudent->first_name . " " . $mStudent->last_name); ?>";
      var user_token = "<?php p(_token());?>";
      confirmBox("Student Remove", "Do you want to remove "+student_name+"?", function(note) {
        App.callAPI("api/student/remove",
          {
            student_id: student_id,
						user_token: user_token
          })
        .done(function(res) {
                alertBox("Student Remove", student_name+" removed successfully", function() {
									goto_url("<?php p(_url("student/index/1")); ?>");
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
  var select_url = "<?php p(_url("subclass/multi_select//1/"));?>";

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
}
</script>