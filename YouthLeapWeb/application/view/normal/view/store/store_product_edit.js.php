<script type="text/javascript">

$(function () {
	$('#form_common').ajaxForm({
		dataType : 'json',
		success: function(ret, statusText, xhr, form) {
			try {
				if (ret.err_code == 0)
				{	
					alertBox("Success", "Product Info is updated.", function() {
						goto_url("<?php p(_url("store/product")); ?>");
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
		
  $('.btn-remove').click(function() {
      var product_id = "<?php p($mProduct->id); ?>";
      var product_name = "<?php p($mProduct->product_name); ?>";
      confirmBox("Student Remove", "Do you want to remove "+product_name+"?", function(note) {
        App.callAPI("api/student/remove",
          {
            product_id: product_id
          })
        .done(function(res) {
                alertBox("Student Remove", product_name+" removed successfully", function() {
									goto_url("<?php p(_url("student/index/1")); ?>");
                });
              })
              .fail(function(res) {
                errorBox("Remove Error", res.err_msg);
              });
      });
    });
});

</script>