<script type="text/javascript">

$(function() {
	$('#btn_insert').click(function() {
		var action_url = $(this).attr("action");
		App.callAPI("api/store/category_save", {
			id : $("#id").val(),
      category_name : $("#category_name").val()
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