<script type="text/javascript">

$(function () {
	refresh_student();
	$('#form_common').ajaxForm({
		dataType : 'json',
		success: function(ret, statusText, xhr, form) {
			try {
				if (ret.err_code == 0)
				{	
					alertBox("Success", "Personal info is updated.", function() {
						goto_url("<?php p(_url("parent/index")); ?>");
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
      var parent_id = "<?php p($mParent->id); ?>";
      var parent_name = "<?php p($mParent->first_name . " " . $mParent->last_name); ?>";
      var user_token = "<?php p(_token());?>";
      confirmBox("Parent Active", "Do you want to active "+parent_name+"?", function(note) {
        App.callAPI("api/parent/active",
          {
            parent_id: parent_id,
            is_active: 1,
						user_token: user_token
          })
        .done(function(res) {
                alertBox("Parent Active", parent_name+" actived successfully", function() {
									goto_url("<?php p(_url("parent/index")); ?>");
                });
              })
              .fail(function(res) {
                errorBox("Active Error", res.err_msg);
              });
      });
    });
  
  $('.btn-inactive').click(function() {
      var parent_id = "<?php p($mParent->id); ?>";
      var parent_name = "<?php p($mParent->first_name . " " . $mParent->last_name); ?>";
      var user_token = "<?php p(_token());?>";
      confirmBox("Parent Inactive", "Do you want to inactive "+parent_name+"?", function(note) {
        App.callAPI("api/parent/active",
          {
            parent_id: parent_id,
            is_active: 0,
						user_token: user_token
          })
        .done(function(res) {
                alertBox("Parent Inactive", parent_name+" inactived successfully", function() {
									goto_url("<?php p(_url("parent/index")); ?>");
                });
              })
              .fail(function(res) {
                errorBox("Inactive Error", res.err_msg);
              });
      });
		});
		
  $('.btn-remove').click(function() {
      var parent_id = "<?php p($mParent->id); ?>";
      var parent_name = "<?php p($mParent->first_name . " " . $mParent->last_name); ?>";			
      var user_token = "<?php p(_token());?>";
      confirmBox("Parent Remove", "Do you want to remove "+parent_name+"?", function(note) {
        App.callAPI("api/parent/remove",
          {
            parent_id: parent_id,
						user_token: user_token
          })
        .done(function(res) {
                alertBox("Parent Remove", parent_name+" removed successfully", function() {
									goto_url("<?php p(_url("parent/index")); ?>");
                });
              })
              .fail(function(res) {
                errorBox("Remove Error", res.err_msg);
              });
      });
    });
});

function on_select_student(students)
{
	v = "";
	for(i = 0; i < students.length; i ++) {
		if (v != "")
			v += ";";

		v += students[i].student_id + ":" + students[i].student_name;
	}

	$('#students').val(v);

	refresh_student();
}

function refresh_student()
{
	var lks = "";
	var students = $('#students').val();
  var student_ids = "";
  var select_url = "<?php p(_url("student/multi_select/0/"));?>";

	if (students != "")
	{
		var ps = students.split(';');
		for (i = 0; i < ps.length; i ++)
		{
			var fs = ps[i].split(':');
			lks += "<span>" + fs[1] + " <a href='javascript:;' class='remove-student' no=" + i + "><i class='ln-icon-cross2'></i></a>";
			lks += " </span>";

			select_url += fs[0] + "/";

			if (student_ids != "")
				student_ids += ",";
			student_ids += fs[0];
		}
		$('#student_list').html(lks);

		$('.remove-student').click(function() {
			var no = $(this).attr('no');
			var students = $('#students').val();
			var new_student = "";
			if (students != "")
			{
				var ps = students.split(';');
				for (i = 0; i < ps.length; i ++)
				{
					if (i != no)
					{
						if (new_student != "")
							new_student += ";";
						new_student += ps[i];
					}
				}
			}
			$('#students').val(new_student);
			refresh_student();
		});
	}
	else {
		$('#student_list').html(lks);

	}
	select_url += "?callback=on_select_student";
	$('#student_select').attr("href", select_url);
}
</script>