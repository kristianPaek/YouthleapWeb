<script type="text/javascript">

$(function () {
  $(".btn_lookup_remove").click(function() {
    var lookup_id = $(this).attr("lookup_id");
    var lookup_name = $(this).attr("lookup_name");
    confirmBox("Lookup Remove", "Do you want to remove "+lookup_name+"?", function(note) {
      App.callAPI("api/master/lookup_remove",
        {
          lookup_id: lookup_id
        })
      .done(function(res) {
        alertBox("Lookup Remove", lookup_name+" removed successfully", function() {
          goto_url("<?php p(_url("master/lookup")); ?>");
        });
      })
      .fail(function(res) {
        errorBox("Remove Error", res.err_msg);
      });
    });
  });
});
function on_update() {
  location.reload();
}
</script>