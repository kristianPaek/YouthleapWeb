<script type="text/javascript">
$(function () {
  $('.btn-remove').click(function() {
      var video_id = $(this).attr("video_id");
      var video_name = $(this).attr("video_name");
      confirmBox("Video Remove", "Do you want to remove "+video_name+"?", function(note) {
        App.callAPI("api/video/remove",
          {
            video_id: video_id
          })
        .done(function(res) {
            alertBox("Student Remove", video_name+" removed successfully", function() {
              location.reload();
            });
          })
          .fail(function(res) {
            errorBox("Remove Error", res.err_msg);
          });
      });
    });
});
</script>