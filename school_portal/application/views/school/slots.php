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
?>

<!-- .row -->
<div class="row">
    <div class="col-sm-12">
        <div class="white-box">
            <h3 class="box-title m-b-0"><?=$title?> <a href="<?=base_url("configuration/slots/add")?>" class="btn btn-success pull-right"><i class="fa fa-plus"></i>ADD SLOT</a></h3><br>


            <div class="table-responsive">
                <table id="example23" class="display nowrap table table-hover table-striped" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Class</th>
                        <th>Start Time</th>
                        <th>Slot Duration<br>(Before Interval)</th>
                        <th>Interval</th>
                        <th>Slot Duration<br>(After Interval)</th>
                        <th>End Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Class</th>
                        <th>Start Time</th>
                        <th>Slot Duration<br>(Before Interval)</th>
                        <th>Interval</th>
                        <th>Slot Duration<br>(After Interval)</th>
                        <th>End Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach ($slots as $s){
                        $count++;
                        ?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$s->class?></td>
                            <td><?=$s->b_start_time?></td>
                            <td><?=$s->b_slot_duration?></td>
                            <td><?=$s->b_end_time." - ".$s->a_start_time?></td>
                            <td><?=$s->a_slot_duration?></td>
                            <td><?=$s->a_end_time?></td>
                            <td><?=$s->is_active?></td>
                            <td><a class="btn btn-danger" href="<?=base_url("configuration/slots/edit/$s->slotID")?>"><i class="fa fa-edit"></i></a></td>
                        </tr>

                    <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.row -->
