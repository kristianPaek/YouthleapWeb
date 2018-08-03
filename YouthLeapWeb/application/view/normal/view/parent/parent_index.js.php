<script type="text/javascript">

$(function () {
  $('.btn-remove').click(function() {
      var parent_id = $(this).parent().attr("parent_id");
      var parent_name = $(this).parent().attr("parent_name");
      var user_token = "<?php p(_token());?>";
      confirmBox("Parent Remove", "Do you want to remove "+parent_name+"?", function(note) {
        App.callAPI("api/parent/remove",
          {
            parent_id: parent_id,
            user_token: user_token
          })
        .done(function(res) {
                alertBox("Parent Remove", parent_name+" removed successfully", function() {
                  location.reload();
                });
              })
              .fail(function(res) {
                errorBox("Remove Error", res.err_msg);
              });
      });
    });
  
  $('.btn-active').click(function() {
      var parent_id = $(this).parent().attr("parent_id");
      var parent_name = $(this).parent().attr("parent_name");
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
                  location.reload();
                });
              })
              .fail(function(res) {
                errorBox("Active Error", res.err_msg);
              });
      });
    });
  
  $('.btn-inactive').click(function() {
      var parent_id = $(this).parent().attr("parent_id");
      var parent_name = $(this).parent().attr("parent_name");
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