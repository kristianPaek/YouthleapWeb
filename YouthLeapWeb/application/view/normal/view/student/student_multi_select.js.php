<script type="text/javascript">

$(function() {
	$('#btn_insert').click(function() {
		students = [];
		$('.checkbox-student').each(function() {
			if ($(this).is(':checked')) {
				students.push({
					student_id: $(this).val(),
					student_name: $(this).attr('student_name')
				});
			}
		});

        <?php if ($this->callback) { ?>
        parent.<?php p($this->callback); ?>(students);
        parent.$.fancybox.close();
        <?php } ?>
	});

	$('#search_string').change(function() {
		query = $(this).val();

		if (query == '')
		{
			$('.tr-student').show();
		}
		else {
			$('.tr-student').each(function() {
				student_name = $(this).find('.checkbox-student').attr('student_name');

				if (student_name.match(query))
					$(this).show();
				else
					$(this).hide();
			});
		}
	});
})
</script>