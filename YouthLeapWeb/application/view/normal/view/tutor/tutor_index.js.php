<script type="text/javascript">

$(function () {
	$('.btn-remove').click(function() {
      var tutor_id = $(this).parent().attr("tutor_id");
      var tutor_name = $(this).parent().attr("tutor_name");
			confirmBox("Tutor Remove", "Do you want to remove "+tutor_name+"?", function(note) {
				App.callAPI("api/tutor/remove",
					{
						tutor_id: tutor_id
					})
				.done(function(res) {
								alertBox("Tutor Remove", tutor_name+" removed successfully", function() {
									location.reload();
								});
							})
							.fail(function(res) {
								errorBox("Remove Error", res.err_msg);
							});
			});
		});
  
  $('.btn-active').click(function() {
      var tutor_id = $(this).parent().attr("tutor_id");
      var tutor_name = $(this).parent().attr("tutor_name");
      confirmBox("Tutor Active", "Do you want to active "+tutor_name+"?", function(note) {
        App.callAPI("api/tutor/active",
          {
            tutor_id: tutor_id,
            is_active: 1,
          })
        .done(function(res) {
                alertBox("Tutor Active", tutor_name+" actived successfully", function() {
                  location.reload();
                });
              })
              .fail(function(res) {
                errorBox("Active Error", res.err_msg);
              });
      });
    });
  
  $('.btn-inactive').click(function() {
      var tutor_id = $(this).parent().attr("tutor_id");
      var tutor_name = $(this).parent().attr("tutor_name");
      confirmBox("Tutor Inactive", "Do you want to inactive "+tutor_name+"?", function(note) {
        App.callAPI("api/tutor/active",
          {
            tutor_id: tutor_id,
            is_active: 0,
          })
        .done(function(res) {
                alertBox("Tutor Inactive", tutor_name+" inactived successfully", function() {
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