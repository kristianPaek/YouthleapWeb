<script type="text/javascript">

$(function () {
  $(".grade-item").click(function() {
    var grade_id = $(this).attr("grade_id");
    var is_active = $(this).hasClass("active");
    $(".grade-item").removeClass("active");
    if (!is_active) {
      $(this).addClass("active");
      App.callAPI("api/subclass/get_classlist", {
          parent_id: grade_id
      }).done(function(res) {
        if (res.err_code == 0) {
          var content = "";
          for(var i=0; i<res.classlist.length; i++) {
            var item = res.classlist[i];
            if (i == 0) {
              content += "<div class='row'>";
            }
            content += "<div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>";
            content += "<div class='class-item' class_id='"+item.class_id+"'>";
            content += "<div class='text-center'>";
            content += "  <i class='icon icon-graduation class-icon'></i>";
            content += "</div>"
            content += "<h4 class='text-center class-text'>"+item.class_name+"</h4>";
            content += "<div class='action text-right'>";
            content += "  <a href='master/grade_edit/"+item.class_id+"?callback=on_update' class='fancybox' fancy-width='450' fancy-height='320' title='Edit'><i class='icon-note'></i></a>";
            content += "  <a href='student/item' class='btn-add-cart' title='Remove'><i class='ln-icon-trash2'></i></a>";
            content += "</div>";
            content += "</div>";
            content += "</div>";
          }
          if (res.classlist.length > 0) {
            content += "</div>";
          }
          $("#class_list").html(content);
          App.initPopup("<?php p(HOME_BASE); ?>");
        }
      });
    } else {
      $("#class_list").html("");
    }
  });

  $(".btn_grade_remove").click(function() {
    var grade_id = $(this).attr("grade_id");
    var grade_name = $(this).attr("grade_name");
    confirmBox("Grade Remove", "Do you want to remove "+grade_name+"?", function(note) {
      App.callAPI("api/master/class_remove",
        {
          class_id: grade_id
        })
      .done(function(res) {
        alertBox("Grade Remove", grade_name+" removed successfully", function() {
          goto_url("<?php p(_url("master/index")); ?>");
        });
      })
      .fail(function(res) {
        errorBox("Remove Error", res.err_msg);
      });
    });
  });

  $(".btn_subject_remove").click(function() {
    var subject_id = $(this).attr("subject_id");
    var subject_name = $(this).attr("subject_name");
    confirmBox("Subject Remove", "Do you want to remove "+subject_name+"?", function(note) {
      App.callAPI("api/master/subject_remove",
        {
          subject_id: subject_id
        })
      .done(function(res) {
        alertBox("Subject Remove", subject_name+" removed successfully", function() {
          goto_url("<?php p(_url("master/index")); ?>");
        });
      })
      .fail(function(res) {
        errorBox("Remove Error", res.err_msg);
      });
    });
  });

  $(".btn_semester_remove").click(function() {
    var semester_id = $(this).attr("semester_id");
    var semester_name = $(this).attr("semester_name");
    confirmBox("Semester Remove", "Do you want to remove "+semester_name+"?", function(note) {
      App.callAPI("api/master/semester_remove",
        {
          semester_id: semester_id
        })
      .done(function(res) {
        alertBox("Semester Remove", semester_name+" removed successfully", function() {
          goto_url("<?php p(_url("master/index")); ?>");
        });
      })
      .fail(function(res) {
        errorBox("Remove Error", res.err_msg);
      });
    });
  });

  $(".btn_standard_remove").click(function() {
    var standard_id = $(this).attr("standard_id");
    var standard_name = $(this).attr("standard_name");
    confirmBox("Standard Remove", "Do you want to remove "+standard_name+"?", function(note) {
      App.callAPI("api/master/standard_remove",
        {
          standard_id: standard_id
        })
      .done(function(res) {
        alertBox("Standard Remove", standard_name+" removed successfully", function() {
          goto_url("<?php p(_url("master/index")); ?>");
        });
      })
      .fail(function(res) {
        errorBox("Remove Error", res.err_msg);
      });
    });
  });

  $(".btn_period_remove").click(function() {
    var period_id = $(this).attr("period_id");
    var period_name = $(this).attr("period_name");
    confirmBox("Marking Period Remove", "Do you want to remove "+period_name+"?", function(note) {
      App.callAPI("api/master/period_remove",
        {
          period_id: period_id
        })
      .done(function(res) {
        alertBox("Marking Period Remove", period_name+" removed successfully", function() {
          goto_url("<?php p(_url("master/index")); ?>");
        });
      })
      .fail(function(res) {
        errorBox("Remove Error", res.err_msg);
      });
    });
  });
  
  $(".btn_year_remove").click(function() {
    var year_id = $(this).attr("year_id");
    var year_name = $(this).attr("year_name");
    confirmBox("Year Remove", "Do you want to remove "+year_name+"?", function(note) {
      App.callAPI("api/master/year_remove",
        {
          year_id: year_id
        })
      .done(function(res) {
        alertBox("Year Remove", year_name+" removed successfully", function() {
          goto_url("<?php p(_url("master/index")); ?>");
        });
      })
      .fail(function(res) {
        errorBox("Remove Error", res.err_msg);
      });
    });
  });
});
function on_update() {
  location.reload();
}
</script>