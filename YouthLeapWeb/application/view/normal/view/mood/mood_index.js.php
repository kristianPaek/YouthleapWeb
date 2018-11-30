<script type="text/javascript">
$(function () {
  get_mood_statics($("#event_id").val());
  var options = {
    startDate: moment(new Date("<?php p($mEvent->from_date);?>")),
    endDate: moment(new Date("<?php p($mEvent->to_date);?>"))
	}
  $('.datepaginator').datepaginator(options);

  $("#event_id").change(function() {
    $(".mood-datas").empty();
    $("#chartContainer").empty();
    $("#pieChart").empty();
    get_mood_statics($("#event_id").val());
    var options = {
      startDate: moment(new Date("<?php p($mEvent->from_date);?>")),
      endDate: moment(new Date("<?php p($mEvent->to_date);?>"))
    }
    $('.datepaginator').datepaginator(options);
    $('.datepaginator').on('selectedDateChanged', function (event, date) {
      get_mood_list($("#event_id").val(), moment(date).format("YYYY-MM-DD"));
    });
  });
  $('.datepaginator').on('selectedDateChanged', function (event, date) {
    get_mood_list($("#event_id").val(), moment(date).format("YYYY-MM-DD"));
  });
});
function get_mood_list(event_id, date) {
  App.callAPI("api/mood/get_moodlist", {
    user_id : "<?php p(_user_sub_id());?>",
    event_id : event_id,
    mood_date: date,
    user_token: "<?php p(_token());?>"
    }).done(function(res) {
      if (res.err_code == 0) {
        $(".mood-datas").empty();
        for (var i =0; i < res.moods.length; i++) {
          var mood = res.moods[i];
          mood_item = "";
          mood_item += "<li style='background:"+mood.mood.color+"'>";
          mood_item += "  <div style='display:flex'>";
          mood_item += "    <img src='"+mood.mood.mood_image+"' style='margin: 5px 20px;'/>";
          mood_item += "    <div style='padding-top: 5px; padding-bottom: 5px;color:"+mood.mood.font_color+";display:grid;align-items:center;width:70%'>";
          mood_item += "      <label style='display:block;'>"+mood.student.first_name + " " + mood.student.last_name+"</label>";
          mood_item += "      <label style='display:block;'>"+mood.mood.phrase+"</label>";
          mood_item += "      <span>Class1<em style='float:right;'>"+mood.mood.mood_date+"</em></span>";
          mood_item += "    </div>";
          mood_item += "  </div>";
          mood_item += "</li>";
          $(".mood-datas").append(mood_item);
        }
      } else {
        $(".mood-datas").empty();
      }
    });
}

function get_mood_statics_by_date(event_id)
{
  App.callAPI("api/mood/get_mood_statics", {
    user_id : "<?php p(_user_sub_id());?>",
    event_id : event_id,
    user_token: "<?php p(_token());?>",
    by_date: false
    }).done(function(res) {
      if (res.err_code == 0) {
        show_mood_charts(res.results, false);
        get_mood_list($("#event_id").val(), $(".datepaginator .dp-selected").attr("data-moment"));
      }
    });
}

function get_mood_statics(event_id) {
  App.callAPI("api/mood/get_mood_statics", {
    user_id : "<?php p(_user_sub_id());?>",
    event_id : event_id,
    user_token: "<?php p(_token());?>",
    by_date: true
    }).done(function(res) {
      if (res.err_code == 0) {
        show_mood_charts(res.results, true);
        get_mood_statics_by_date($("#event_id").val());
      }
    });
}

function show_mood_charts(mood_datas, by_date, mood_date="")
{
  if (!by_date) {
    var piechart = new CanvasJS.Chart("pieChart", {
      theme: "light2", // "light1", "light2", "dark1", "dark2"
      exportEnabled: true,
      animationEnabled: true,
      title: {
        text: "Mood Statistics " + mood_date
      },
      data: [{
        type: "pie",
        startAngle: 25,
        toolTipContent: "<b>{label}</b>: {y}",
        showInLegend: "true",
        legendText: "{label}",
        indexLabelFontSize: 16,
        indexLabel: "{label} - {y}",
        dataPoints: [
          { y: mood_datas.mad, label: "Mad", color: "#f60000" },
          { y: mood_datas.sad, label: "Sad", color: "#1e2664" },
          { y: mood_datas.clam, label: "Clam", color: "#1f7d0f" },
          { y: mood_datas.brave, label: "Brave", color: "#f4e715" }
        ]
      }]
    });
    piechart.render();
  }
  else {
    mads = [];
    sads = [];
    clams = [];
    braves = [];
    $.each(mood_datas, function( key, value ) {
      mads.push({x: new Date(value.date), y: value.mood_data.mad});
      sads.push({x: new Date(value.date), y: value.mood_data.sad});
      clams.push({x: new Date(value.date), y: value.mood_data.clam});
      braves.push({x: new Date(value.date), y: value.mood_data.brave});
    });
    var chart = new CanvasJS.Chart("chartContainer", {
      animationEnabled: true,
      title:{
        text: "Mood Charts, " + "<?php p($mEvent->from_date . "~" . $mEvent->to_date); ?>"
      },
      axisY :{
        includeZero: false
      },
      toolTip: {
        shared: true
      },
      legend: {
        fontSize: 13
      },
      data: [{
        type: "spline",
        showInLegend: true,
        name: "Mad",
        color: "#f60000",
        dataPoints: mads
      },
      {
        type: "spline", 
        showInLegend: true,
        name: "Sad",
        color: "#1e2664",
        dataPoints: sads
      },
      {
        type: "spline", 
        showInLegend: true,
        name: "Clam",
        color: "#1f7d0f",
        dataPoints: clams
      },
      {
        type: "spline", 
        showInLegend: true,
        name: "Brave",
        color: "#f4e715",
        dataPoints: braves
      }]
    });
    chart.render();
  }
}
</script>