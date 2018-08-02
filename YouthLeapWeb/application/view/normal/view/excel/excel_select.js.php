<script type="text/javascript">

$(function() {
	var new_list = null;
	$('#btn_insert').click(function() {
		var action_url = $(this).attr("action");
		App.callAPI(action_url, {
			new_list : new_list
	    }).done(function(res) {
	        if (res.err_code == 0) {
						alertBox("Success", "Excel File is uploaded.");
						parent.$.fancybox.close();
						goto_url("<?php p(_url($this->goto_url)); ?>");
	        }
					else if (ret.err_msg != "")
					{
						errorBox("Error", ret.err_msg);
					}
	    });
	});

	$('#form_file').ajaxForm({
		dataType : 'json',
		success: function(ret, statusText, xhr, form) {
			try {
				if (ret.err_code == 0)
				{	
					alertBox("Success", "Excel File is uploaded.");
					for(i = 0 ; i<ret.data.length ;i++) {
						var row = ret.data[i];
						var row_text = "<tr>";
						for (var key in row) {
							row_text += "<td>"+row[key]+"</td>";
						}
						row_text += "</tr>";
						$("#list_body").append(row_text);
					}
					new_list = ret.data;
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

	$('#excel_file').change(function(e) {
		var path = e.target.value;
		$("#list_body").html("");
		$('#form_file').submit();
	});
});
</script>