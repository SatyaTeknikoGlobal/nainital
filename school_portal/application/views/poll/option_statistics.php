<script type="text/javascript" src="<?php echo base_url(); ?>assets/graph.js"></script>
<script type="text/javascript">
    google.load("visualization", "1.1", {packages: ["bar"]});
    google.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Answers', 'option 1', 'option 2', 'option 3' , 'option 4'],
            <?php
            // foreach ($chart_data as $data) {
            // echo '[' . $data->performance_year . ',' . $data->performance_sales . ',' . $data->performance_expense . ',' . $data->performance_profit . '],';
            echo '[' . $year . ',' . $option1 . ',' . $option2 . ',' . $option3 . ',' . $option4 .'],';
            // }
            ?>
        ]);



        var options = {
            chart: {
                title: 'Quetions Details',
                subtitle: 'Option1, Option2, Option3,Option4',
            }
        };



        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
        //var chart1 = new google.charts.Bar(document.getElementById('columnchart_material1'));
        chart.draw(data, options);
        // chart1.draw(data1, options1);
    }
</script>
<div class="row">
    <div class="col-sm-12">
        <div class="white-box">
            <h3 class="box-title m-b-0"><?=$title?></h3>
            <br>
            <div id="columnchart_material" style="width: 500px; height: 500px;"></div><br><br>
        </div>
    </div>

</div>