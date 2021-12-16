
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <title></title>
        <!-- Load Google chart api -->
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/graph.js"></script>
        <script type="text/javascript">
            google.load("visualization", "1.1", {packages: ["bar"]});
            google.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Answers', 'Yes', 'No'],
<?php
// foreach ($chart_data as $data) {
    // echo '[' . $data->performance_year . ',' . $data->performance_sales . ',' . $data->performance_expense . ',' . $data->performance_profit . '],';
     echo '[' . $year . ',' . $y . ',' . $n. '],';
// }
?>
                ]);

                              
 
                var options = {
                    chart: {
                        title: 'Quetions Details',
                        subtitle: 'Yes, No',
                    }
                };

                 
 
                var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
  //var chart1 = new google.charts.Bar(document.getElementById('columnchart_material1'));
                chart.draw(data, options);
                // chart1.draw(data1, options1);
            }
        </script>
     
    </head>

    <body>
    <div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-sitemap"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
           
          
        </ol>
    </div><!-- /.box-header -->  
       <div class="box-body">    
       <div class="row">  
       <div class="col-md-12">
       <h1>Voting Statistics</h1>
       <br>
       
        <div id="columnchart_material" style="width: 500px; height: 500px;"></div>
        <br><br>
         

          <!-- <h2>According Class Chart</h2>
        <div id="columnchart_material1" style="width: 500px; height: 500px;"></div>
        <br><br>
           -->
        </div>
        </div>

        </div>
        </div>
    </body>
</html>