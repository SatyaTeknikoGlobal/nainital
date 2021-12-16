<?php
/*
| -----------------------------------------------------
| PRODUCT NAME: 	Tekniko School
| -----------------------------------------------------
| AUTHOR:			Kshitij Kumar Singh
| -----------------------------------------------------
| EMAIL:			kshitij.singh@teknikoglobal.com
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY TEKNIKOGLOBAL
| -----------------------------------------------------
| WEBSITE:			https://www.teknikoglobal.com
| -----------------------------------------------------
*/
/*var_dump($attendance);
echo "<br>";
var_dump($default_attendance);*/
?>

<style>

    td.fc-other-month .fc-day-number {
        display: none;
    }

</style>
<!-- row -->
<div class="row">
    <div class="col-md-12">
        <div class="white-box">
            <div id="calendar"></div>
        </div>
    </div>
</div>
<!-- /.row -->
<script>
        $(document).ready(function () {

            $(".fc-day.fc-sun").css({"background":"#ffeb3b","color":"#ffffff"});
            $(".fc-day.fc-widget-content.fc-other-month.fc-past").css({"background":"#fff","color":"#ffffff"});
            $(".fc-day.fc-widget-content.fc-other-month.fc-future").css({"background":"#fff","color":"#ffffff"});
            <?php
            $monthyear = $attendance->monthyear;
            $t = date('t',strtotime($monthyear));
            for($i = 1;$i<=$t;$i++){
                $a = 'a'.$i;
                $date = date("Y-m-d",strtotime($i.'-'.$attendance->monthyear));
                $present = "<div class='circle' style='background:#00cb56;padding-top: 20%'><span style='font-size:2em'>$i</span><br><span class = 'attendance_report' style='font-size: 1.5em;'>P</span></div>";
                $absent = "<div class='circle' style='background:#473F97;padding-top: 20%'><span style='font-size:2em'>$i</span><br><span class = 'attendance_report' style='font-size: 1.5em;'>A</span></div>";
                $holiday = "<div class='circle' style='background:#000;padding-top: 20%'><span style='font-size:2em'>$i</span><br><span class = 'attendance_report' style='font-size: 1.5em;'>H</span></div>";
                $leave = "<div class='circle' style='background:#473F97;padding-top: 20%'><span style='font-size:2em'>$i</span><br><span class = 'attendance_report' style='font-size: 1.5em;'>L</span></div>";
                if (strtolower($attendance->$a) == 'p'){
                    echo '$("[data-date='.$date.']").html("'.$present.'");';
                }elseif (strtolower($attendance->$a) == 'a') {
                    echo '$("[data-date='.$date.']").html("'.$absent.'");';
                }elseif (strtolower($attendance->$a) == 'l') {
                    echo '$("[data-date='.$date.']").html("'.$leave.'");';
                }
            }
            ?>
        });
    </script>
<script>
        $(document).ready(function () {
            $("#calendar .fc-header-toolbar .fc-left h2").on('DOMSubtreeModified',function () {
                if ($(this).text() != "")
                {
                    var nmy = $(this).text();
                    var subjectID = 0;
                    $.ajax({
                        url : window.location.href,
                        type : "post",
                        data : "subjectID="+subjectID+"&monthyear="+nmy,
                        success : function (response) {
                            var i;
                            response = JSON.parse(response);

                            if (response[0]['attendance']){

                                for (i=1;i<=response[0]['end'];i++){
                                    var present = "<span class = 'attendance_report' style='font-size:1.5em'>P</span>";
                                    var absent = "<span class = 'attendance_report' style='font-size:1.5em'>A</span>";
                                    var holiday = "<span class = 'attendance_report' style='font-size:1.5em'>H</span>";
                                    var leave = "<span class = 'attendance_report' style='font-size:1.5em'>L</span>";
                                    var dd = ("0" + i).slice(-2);
                                    var date = response[0]['yearmonth']+"-"+dd;
                                    var day = "a"+i;
                                    if (response[0]['attendance'][day])
                                    {
                                        if (response[0]['attendance'][day].toLowerCase()== "p")
                                        {
                                            $("[data-date = "+date+"]").html("<div class='circle' style = 'background:#00cb56;padding-top: 20%'><span style='font-size:2em'>"+i+"</span><br>"+present+"</div>");
                                        }else if (response[0]['attendance'][day].toLowerCase()== "a")
                                        {
                                            $("[data-date = "+date+"]").html("<div class='circle' style = 'background:#473F97;padding-top: 20%'><span style='font-size:2em'>"+i+"</span><br>"+absent+"</div>");
                                        }else if (response[0]['attendance'][day].toLowerCase()== "l")
                                        {
                                            $("[data-date = "+date+"]").html("<div class='circle' style = 'background:#473F97;padding-top: 20%'><span style='font-size:2em'>"+i+"</span><br>"+leave+"</div>");
                                        }
                                    }
                                }
                            }else{
                                $(".attendance_back").css({"background":"none","color":"#000000"});
                                $(".attendance_report").html("");
                            }
                            $(".fc-day.fc-sun").css({"background":"#ffeb3b","color":"#ffffff"});
                            $(".fc-day.fc-widget-content.fc-other-month.fc-past").css({"background":"#fff","color":"#ffffff"});
                            $(".fc-day.fc-widget-content.fc-other-month.fc-future").css({"background":"#fff","color":"#ffffff"});


                        }
                    });
                }
            });
        });
    </script>

<script>
    $(document).ready(function () {
        $(".fc-day.fc-sun").css({"background":"#ffeb3b","color":"#ffffff"});
        $(".fc-day.fc-widget-content.fc-other-month.fc-past").css({"background":"#fff","color":"#ffffff"});
        $(".fc-day.fc-widget-content.fc-other-month.fc-future").css({"background":"#fff","color":"#ffffff"});
    });
</script>
