<?php
/*
| -----------------------------------------------------
| PRODUCT NAME: 	MENTOR ERP
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
?>
<!-- .row -->
<div class="row">
    <div class="col-sm-12">
        <div class="white-box table-responsive">
            <h3 class="box-title m-b-0 text-center"><span style="float: left"><?=$student->name?></span><span><?=$student->class.'-'.$student->section?></span></h3>
            <p class="text-muted m-b-30 font-13">  </p>
            <table id="result_show" class="table table-bordered">
                <thead>
                <tr>
                    <th class="text-center">EXAM<br>SUBJECT</th>
                    <?php foreach ($exam as $e){ ?>
                    <th class="text-center"><?=$e->exam_title ?></th>
                    <?php }?>
                    <th class="text-center">SUBTOTAL</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($subject as $s){?>
                    <tr>
                        <th class="text-center"><?=$s->subject_name?></th>
                        <?php
                        $obtained = 0;
                        $total = 0;
                        foreach ($exam as $e){
                            echo "<td class='text-center'>";
                            foreach ($exam_result as $er){
                               if ($er->subject == $s->subject_name && $er->examID == $e->examID){
                                   $obtained += $er->mark_obtain;
                                   $total += $er->total_mark;
                                   echo $er->mark_obtain.' / '.$er->total_mark;
                               }
                             }
                             echo "</td>";
                        }
                        echo "<td class='text-center'>".$obtained.' / '.$total."</td>";
                        ?>
                    </tr>
                 <?php }?>
                <tr>
                    <th class="text-center">TOTAL</th>
                    <?php
                    $obtained = 0;
                    $total = 0;
                    foreach ($exam as $e){
                        $e_obtained = 0;
                        $e_total = 0;
                        echo "<th class='text-center'>";
                        foreach ($exam_result as $er){
                            if ($er->examID == $e->examID){
                                $obtained += $er->mark_obtain;
                                $total += $er->total_mark;
                                $e_obtained += $er->mark_obtain;
                                $e_total += $er->total_mark;
                            }
                        }
                        echo $e_obtained.' / '.$e_total."</th>";
                    }
                    echo "<th class='text-center'>".$obtained.' / '.$total."</th>";
                    ?>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- /.row -->