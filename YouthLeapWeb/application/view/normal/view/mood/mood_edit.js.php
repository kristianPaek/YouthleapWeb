<script type="text/javascript">
$(function () {
  $(".mood-img").click(function() {
	if ($(this).parent().hasClass("active")) {
	  $(".mood-img").parent().removeClass("active");
	  $("#mood_id").val(null);
	} else {
	  $(".mood-img").parent().removeClass("active");
	  $(this).parent().addClass("active");
	  $("#mood_id").val($(this).attr("mood_id"));
	  $("#select_image").attr("src", $(this).attr("image_name"));
	}
  });

  $(".btn-color").click(function() {
	if ($(this).hasClass("active")) {
	  $(this).removeClass("active");
	} else {
		$(".btn-color").removeClass("active");
	  $(this).addClass("active");
		$("#color_name").val($(this).attr("color_name"));
	}
  });
	
	$('#form').ajaxForm({
		dataType : 'json',
		success: function(ret, statusText, xhr, form) {
			try {
				if (ret.err_code == 0)
				{	
					alertBox("Success", "Mood is added.", function() {
						goto_url("<?php p(_url("mood/index")); ?>");
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

	var handleTitle = function(tab, navigation, index) {
  //$(".form-wizard").find('li:has([data-toggle="tab"]):eq(' + index + ") a").tab("show");
	  change_title(tab, navigation, index);
	  return true;
  }

  var change_title = function(tab, navigation, index) {
	  var total = navigation.find('li').length;
	  var current = index + 1;

	  // set wizard title
	  $('.title').html('Mood Add (' + (index + 1) + '<small>/' + total + "</small>Step)");
	  // set done steps
	  jQuery('li', $('#form')).removeClass("done");
	  var li_list = navigation.find('li');
	  for (var i = 0; i < index; i++) {
		  jQuery(li_list[i]).addClass("done");
	  }

	  if (current == 1) {
		  $('#form').find('.button-previous').hide();
	  } else {
		  $('#form').find('.button-previous').show();
	  }

	  if (current >= total) {
		  $('#form').find('.button-next').hide();
		  $('#form').find('.button-submit').show();
	  } else {
		  $('#form').find('.button-next').show();
		  $('#form').find('.button-submit').hide();
	  }
	  App.scrollTo($('.title'));
  }

  handleTitle(null, $('.steps'), 0);

  // default form wizard
  $('#form').bootstrapWizard({
	  'tabClass': 'steps',
	  'nextSelector': '.button-next',
	  'previousSelector': '.button-previous',
	  onTabClick: function (tab, navigation, index, clickedIndex) {
		  var li_list = navigation.find('li');
		  if ($('#mood_id').val() == null) {
			  return false;
		  }
		  if (!jQuery(li_list[clickedIndex]).hasClass("done") && 
			  index + 1 != clickedIndex) {
			  return false;
		  }
		  return handleTitle(tab, navigation, clickedIndex);
	  },
	  onNext: function (tab, navigation, index) {
		  if (index == 1 && $("#mood_id").val() == "") {
			  return false;
		  }
		  if (index == 2 && $('#event_id').val() == "") {
				return false;
		  }

		  return handleTitle(tab, navigation, index);
	  },
	  onPrevious: function (tab, navigation, index) {
		  handleTitle(tab, navigation, index);
	  },
	  onTabShow: function (tab, navigation, index) {
		  
	  }
  });
});
</script>