<script type="text/javascript">
$(function () {
	<?php if ($mVideo->video_type == 0) { ?>
	$("#video_detail").hide();
	<?php } ?>
	$("input[name='video_type']").change(function() {
		if ($(this).val() == <?php p(VIDEO_PUBLIC); ?>) {
			$("#video_detail").hide();
		}
		if ($(this).val() == <?php p(VIDEO_PRIVATE); ?>) {
			$("#video_detail").show();
		}
	});

	$("#video_save").click(function() {
		App.callAPI("api/video/save", {
			video_id : $("#video_id").val(),
			vision: $("#vision").val(),
			video_name: $("#video_name").val(),
			description: $("#description").val(),
			year_id: $("#year_id").val(),
			semester_id: $("#semester_id").val(),
			standard_id: $("#standard_id").val(),
			class_id: $("#class_id").val(),
			subject_id: $("#subject_id").val(),
			lookup_id: $("#lookup_id").val(),
			file: $("#video_player").attr("src"),
			video_type: $("input[name='video_type']:checked").val(),
			user_token: "<?php p(_token()); ?>"
	    }).done(function(res) {
        if (res.err_code == 0) {
					alertBox("Success", "Video is uploaded.", function() {
						goto_url("<?php p(_url("video/index")); ?>");
					});
        }
	    });
	});
});
function upload_video() {
	$('#video_upload').ajaxSubmit({
		dataType : 'json',
		success: function(ret, statusText, xhr, form) {
			try {
				if (ret.err_code == 0) {
					$("#video_player").attr("src", ret.tmp_path);
					$("#video_player")[0].play();
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
}
</script>