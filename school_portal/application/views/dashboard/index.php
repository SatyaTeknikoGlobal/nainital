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
$order = json_decode($others,true);
$day = "";
$att_count = "";
foreach ($student_graph as $student2) {
    if ($day == "") {
        $day = "'".$student2['day']. "'";
    }else{
        $day = $day.", '".$student2['day']. "'";
    }
    if ($att_count == "") {
        $att_count = $student2['count'];
    }else{
        $att_count = $att_count.",".$student2['count'];
    }
}

$tday = "";
$tatt_count = "";
foreach ($teacher_graph as $teacher2) {
    if ($tday == "") {
        $tday = "'".$teacher2['day']. "'";
    }else{
        $tday = $tday.", '".$teacher2['day']. "'";
    }
    if ($tatt_count == "") {
        $tatt_count = $teacher2['count'];
    }else{
        $tatt_count = $tatt_count.",".$teacher2['count'];
    }
}
?>
<style>

    td.fc-other-month .fc-day-number {
        display: none;
    }

</style>
        <!-- /.row -->
        <!-- ============================================================== -->
        <!-- Different data widgets -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div>
                        <h3 class="box-title text-center"><?=$school_info->school_name." ( ".$school_info->school_code." ) "?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div class="row row-in">
                        <div class="col-lg-3 col-sm-6 row-in-br">
                            <ul class="col-in">
                                <li>
                                    <span class="circle circle-md bg-danger"><i class="fas fa-users"></i></span>
                                </li>
                                <li class="col-last">
                                    <h3 class="counter text-right m-t-15"><?=$students_count->student?></h3>
                                </li>
                                <?php 
                                if ($students_count->student > 0 && $tstudents_count->student > 0) {
                                    $ustudent =  ($students_count->student / $tstudents_count->student)*100 ;
                                }else{
                                    $ustudent = 0;
                                }
                                if ($teachers_count->teacher > 0 && $tteachers_count->teacher > 0) {
                                    $uteacher =  ($teachers_count->teacher / $tteachers_count->teacher)*100 ;
                                }else{
                                    $uteacher = 0;
                                }
                                ?>
                                <li class="col-middle">
                                    <h4>Approved Students</h4>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?= $ustudent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $ustudent; ?>%">
                                            <span class="sr-only"><?= $ustudent; ?>% Approved (success)</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-3 col-sm-6 row-in-br  b-r-none">
                            <ul class="col-in">
                                <li>
                                    <span class="circle circle-md bg-info"><i class="fas fa-user-secret"></i></span>
                                </li>
                                <li class="col-last">
                                    <h3 class="counter text-right m-t-15"><?=$teachers_count->teacher?></h3>
                                </li>
                                <li class="col-middle">
                                    <h4>Approved Teachers</h4>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?= $uteacher; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $uteacher; ?>%">
                                            <span class="sr-only"><?= $uteacher; ?>% Approved (success)</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-3 col-sm-6 row-in-br">
                            <ul class="col-in">
                                <li>
                                    <span class="circle circle-md bg-primary"><i class="fas fa-user"></i></span>
                                </li>
                                <li class="col-last">
                                    <h3 class="counter text-right m-t-15"><?=$order['parents']; ?></h3>
                                </li>
                                <li class="col-middle">
                                    <h4>Approved Parent</h4>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                            <span class="sr-only">100% Complete (success)</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-3 col-sm-6 ">
                            <ul class="col-in">
                                <li>
                                    <span class="circle circle-md bg-success"><i class="fas fa-clipboard-check"></i></span>
                                </li>
                                <li class="col-last">
                                    <h3 class="counter text-right m-t-15"><?=$students_present->attendance; ?></h3>
                                </li>
                                <?php
                                if ($students_present->attendance > 0 && $students_count->student > 0) {
                                    $student_attendance_per =  ($students_present->attendance / $students_count->student)*100 ;
                                }else{
                                    $student_attendance_per = 0;
                                }
                                if ($teachers_present->attendance > 0 && $teachers_count->teacher > 0) {
                                    $teacher_attendance_per =  ($teachers_present->attendance / $teachers_count->teacher)*100 ;
                                }else{
                                    $teacher_attendance_per = 0;
                                }

                                ?>
                                <li class="col-middle">
                                    <h4>Student Attendance</h4>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?= $student_attendance_per; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $student_attendance_per; ?>%">
                                            <span class="sr-only"><?= $student_attendance_per; ?>% Complete (success)</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-3 col-sm-6 m-t-10  row-in-br">
                            <ul class="col-in">
                                <li>
                                    <span class="circle circle-md bg-warning"><i class="fas fa-clipboard-check"></i></span>
                                </li>
                                <li class="col-last">
                                    <h3 class="counter text-right m-t-15"><?=$teachers_present->attendance; ?></h3>
                                </li>
                                <li class="col-middle">
                                    <h4>Teacher Attendance</h4>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?= $teacher_attendance_per; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $teacher_attendance_per; ?>%">
                                            <span class="sr-only"><?= $teacher_attendance_per; ?>% Complete (success)</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--row -->
        <!-- /.row -->
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="white-box">
                    <h3 class="box-title">Student Attendance</h3>
                    <div id="student_attendance" style="height: 285px;"></div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="white-box">
                    <h3 class="box-title">Teacher Attendance</h3>
                    <div id="teacher_attendance" style="height: 285px;"></div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="white-box">
                    <h3 class="box-title">Announcements</h3>
                    <table class="table table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Notice</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $count = 0; foreach ($order['announcements'] as $o){ $count++; ?>
                            <tr>
                                <td><?=$count?></td>
                                <td><?=$o['title']?></td>
                                <td><?=$o['notice']?></td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="white-box">
                    <h3 class="box-title">Holidays</h3>
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->

        <!-- ============================================================== -->
    </div>
<script>
    $(document).ready(function () {
        $('.table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>
<script>
    $(document).ready(function(){

        var data = {
          labels: [<?= $day; ?>],
            series: [
            [<?= $att_count; ?>]
          ]
        };

        var options = {
          seriesBarDistance: 15
        };

        var responsiveOptions = [
          ['screen and (min-width: 641px) and (max-width: 1024px)', {
            seriesBarDistance: 10,
            axisX: {
              labelInterpolationFnc: function (value) {
                return value;
              }
            }
          }],
          ['screen and (max-width: 640px)', {
            seriesBarDistance: 5,
            axisX: {
              labelInterpolationFnc: function (value) {
                return value[0];
              }
            }
          }]
        ];

        new Chartist.Bar('#student_attendance', data, options, responsiveOptions);

        var data = {
          labels: [<?= $tday; ?>],
            series: [
            [<?= $tatt_count; ?>]
          ]
        };

        var options = {
          seriesBarDistance: 15
        };

        var responsiveOptions = [
          ['screen and (min-width: 641px) and (max-width: 1024px)', {
            seriesBarDistance: 10,
            axisX: {
              labelInterpolationFnc: function (value) {
                return value;
              }
            }
          }],
          ['screen and (max-width: 640px)', {
            seriesBarDistance: 5,
            axisX: {
              labelInterpolationFnc: function (value) {
                return value[0];
              }
            }
          }]
        ];

        new Chartist.Bar('#teacher_attendance', data, options, responsiveOptions);
    });
</script>
<script>
    $(document).ready(function () {

        $(".fc-day.fc-sun").css({"background":"#ffeb3b","color":"#ffffff"});
        $(".fc-day.fc-widget-content.fc-other-month.fc-past").css({"background":"#fff","color":"#ffffff"});
        $(".fc-day.fc-widget-content.fc-other-month.fc-future").css({"background":"#fff","color":"#ffffff"});
        <?php
        foreach($order['holidays'] as $h)
        {
            $day123 = date("j",strtotime($h['date']));
            $holiday1 = "<div class='circle' style='background:#000;padding-top: 20%'><span style='font-size:2em'>$day123</span><br><span class = 'attendance_report' style='font-size: 1.5em;'>H</span></div>";
            echo '$("[data-date='.$h['date'].']").html("'.$holiday1.'");';
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
                $.ajax({
                    url : "<?=base_url("dashboard/holiday_list/")?>",
                    type : "post",
                    data : "&monthyear="+nmy,
                    success : function (response) {
                        var i;
                        response = JSON.parse(response);

                        if (response[0]['holiday']){

                            for (i=1;i<=response[0]['end'];i++){
                                var present = "<span class = 'attendance_report' style='font-size:1.5em'>P</span>";
                                var absent = "<span class = 'attendance_report' style='font-size:1.5em'>A</span>";
                                var holiday = "<span class = 'attendance_report' style='font-size:1.5em'>H</span>";
                                var leave = "<span class = 'attendance_report' style='font-size:1.5em'>L</span>";
                                var dd = ("0" + i).slice(-2);
                                var date = response[0]['yearmonth']+"-"+dd;
                                var day = "a"+i;
                                for(var j in response[0]['holiday']){
                                    var date1 = response[0]['holiday'][j]['date'];
                                    var dt = new Date(date);
                                    var day123 = dt.getDate();
                                    if (date1 == date){
                                        $("[data-date = "+date+"]").html("<div class='circle' style = 'background:#000;padding-top: 20%'><span style='font-size:2em'>"+day123+"</span><br>"+holiday+"</div>");
                                    }
                                }
                            }
                        }else {
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