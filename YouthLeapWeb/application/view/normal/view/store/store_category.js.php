<script type="text/javascript">
function on_category_update() {
  location.reload();
}

$('.btn-remove').click(function() {
  var category_id = $(this).attr("category_id");
  var category_name = $(this).attr("category_name");
  confirmBox("Category Remove", "Do you want to remove "+category_name+"?", function(note) {
    App.callAPI("api/store/category_remove",
      {
        category_id: category_id
      })
    .done(function(res) {
        alertBox("Category Remove", category_name+" removed successfully", function() {
          location.reload();
        });
      })
      .fail(function(res) {
        errorBox("Remove Error", res.err_msg);
      });
  });
});
</script>