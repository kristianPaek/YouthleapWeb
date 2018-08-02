<script type="text/javascript">

$(function() {
	$('#btn_insert').click(function() {
		subjects = [];
		$('.checkbox-subject').each(function() {
			if ($(this).is(':checked')) {
				subjects.push({
					subject_id: $(this).val(),
					subject_name: $(this).attr('subject_name')
				});
			}
		});

        <?php if ($this->callback) { ?>
        parent.<?php p($this->callback); ?>(subjects);
        parent.$.fancybox.close();
        <?php } ?>
	});

	$('#search_string').change(function() {
		query = $(this).val();

		if (query == '')
		{
			$('.tr-subject').show();
		}
		else {
			$('.tr-subject').each(function() {
				subject_name = $(this).find('.checkbox-subject').attr('subject_name');

				if (subject_name.match(query))
					$(this).show();
				else
					$(this).hide();
			});
		}
	});
})
</script>