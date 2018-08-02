<script type="text/javascript">

$(function() {
	$('#btn_insert').click(function() {
		classs = [];
		$('.checkbox-class').each(function() {
			if ($(this).is(':checked')) {
				classs.push({
					class_id: $(this).val(),
					class_name: $(this).attr('class_name')
				});
			}
		});

        <?php if ($this->callback) { ?>
        parent.<?php p($this->callback); ?>(classs);
        parent.$.fancybox.close();
        <?php } ?>
	});

	$('#search_string').change(function() {
		query = $(this).val();

		if (query == '')
		{
			$('.tr-class').show();
		}
		else {
			$('.tr-class').each(function() {
				class_name = $(this).find('.checkbox-class').attr('class_name');

				if (class_name.match(query))
					$(this).show();
				else
					$(this).hide();
			});
		}
	});
})
</script>