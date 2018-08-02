<script type="text/javascript">

$(function() {
	$('#btn_insert').click(function() {
		var action_url = $(this).attr("action");
		App.callAPI("api/master/semester_save", {
			id : $("#id").val(),
      semester_name : $("#semester").val(),
      semester_code : $("#semester_code").val()
	    }).done(function(res) {
        if (res.err_code == 0) {
          <?php if ($this->callback) { ?>
          parent.<?php p($this->callback); ?>();
          parent.$.fancybox.close();
          <?php } ?>
        }
	    });
	});

	$('#btn_cancel').click(function() {
    parent.$.fancybox.close();
	});
});
</script>