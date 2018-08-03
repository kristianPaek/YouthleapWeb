<script type="text/javascript">

$(function () {
  $('.btn-remove').click(function() {
      var student_id = $(this).parent().attr("student_id");
      var student_name = $(this).parent().attr("student_name");
      var user_token = "<?php p(_token());?>";
      confirmBox("Student Remove", "Do you want to remove "+student_name+"?", function(note) {
        App.callAPI("api/student/remove",
          {
            student_id: student_id,
            user_token: user_token
          })
        .done(function(res) {
            alertBox("Student Remove", student_name+" removed successfully", function() {
              location.reload();
            });
          })
          .fail(function(res) {
            errorBox("Remove Error", res.err_msg);
          });
      });
    });
  
  $('.btn-active').click(function() {
      var student_id = $(this).parent().attr("student_id");
      var student_name = $(this).parent().attr("student_name");
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
                  location.reload();
                });
              })
              .fail(function(res) {
                errorBox("Active Error", res.err_msg);
              });
      });
    });
  
  $('.btn-inactive').click(function() {
      var student_id = $(this).parent().attr("student_id");
      var student_name = $(this).parent().attr("student_name");
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
                  location.reload();
                });
              })
              .fail(function(res) {
                errorBox("Inactive Error", res.err_msg);
              });
      });
    });

});
</script>