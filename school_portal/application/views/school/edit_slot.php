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

$min_time = strtotime($slot->b_end_time ) - strtotime($slot->b_start_time );
$no_lacture1 = $min_time/(60 * $slot->b_slot_duration);
$lunch = (strtotime($slot->a_start_time ) - strtotime($slot->b_end_time ))/60;
$min_time1 = strtotime($slot->a_end_time ) - strtotime($slot->a_start_time );
$no_lacture2 = $min_time1/(60 * $slot->a_slot_duration);
?>

<!-- .row -->
<div class="row">
    <div class="col-sm-8 col-sm-offset-2">
        <div class="white-box">
            <h3 class="box-title m-b-0">Edit Slot</h3>
            <p class="text-muted m-b-30 font-13"> Edit Slot for <?= $slot->class_name ?> </p>
            <form class="form-horizontal" method="post" >
                <div class="form-group">
                    <label class="col-sm-12">Class</label>
                    <div class="col-sm-12">
                        <input type="text" value="<?= $slot->class_name ?>" disabled class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Start Time<span class="help"></span></label>
                    <div class="col-md-12 clockpicker " data-placement="bottom" data-align="top" data-autoclose="true">
                        <input type="text" value="<?= $slot->b_start_time ?>" class="form-control" name="b_start_time" id="b_start_time" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Lecture Duration (Before Interval in minutes)<span class="help"></span></label>
                    <div class="col-md-12 ">
                        <input type="number" value="<?= $slot->b_slot_duration ?>" class="form-control" name="b_slot_duration" id="b_slot_duration" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-12">No. of Lecture (Before Interval)<span class="help"></span></label>
                    <div class="col-md-12 ">
                        <input type="number" value="<?= $no_lacture1 ?>" class="form-control" name="b_lacture" id="b_lacture" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Lunch Duration (in minutes)<span class="help"></span></label>
                    <div class="col-md-12 ">
                        <input type="number" class="form-control" value="<?= $lunch ?>" name="lunch_duration" id="lunch_duration" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Lecture Duration (After Interval in minutes)<span class="help"></span></label>
                    <div class="col-md-12 ">
                        <input type="number" class="form-control" value="<?= $slot->a_slot_duration ?>" name="a_slot_duration" id="a_slot_duration" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">No. of Lecture (After Interval)<span class="help"></span></label>
                    <div class="col-md-12 ">
                        <input type="number" class="form-control" value="<?= $no_lacture2 ?>" name="a_lacture" id="a_lacture" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-12">Status</label>
                    <div class="col-sm-12">
                        <select class="form-control" name="is_active" id="is_active" required>
                            <option <?php if ($slot->is_active == "Y") echo "selected" ;?> value="Y">Active</option>
                            <option <?php if ($slot->is_active == "N") echo "selected" ;?> value="N">InActive</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-success" value="UPDATE SLOT"> </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->
