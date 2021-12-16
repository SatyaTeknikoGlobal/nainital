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
    <div class="col-sm-8 col-sm-offset-2">
        <div class="white-box">
            <h3 class="box-title m-b-0">Add Slot</h3>
            <p class="text-muted m-b-30 font-13"> Add Slot for routine </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-md-12">Start Time<span class="help"></span></label>
                    <div class="col-md-12 clockpicker " data-placement="bottom" data-align="top" data-autoclose="true">
                        <input type="text" class="form-control" name="b_start_time" id="b_start_time" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Lecture Duration (Before Interval in minutes)<span class="help"></span></label>
                    <div class="col-md-12 ">
                        <input type="number" class="form-control" name="b_slot_duration" id="b_slot_duration" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">No. of Lecture (Before Interval)<span class="help"></span></label>
                    <div class="col-md-12 ">
                        <input type="number" class="form-control" name="b_lacture" id="b_lacture" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Lunch Duration (in minutes)<span class="help"></span></label>
                    <div class="col-md-12 ">
                        <input type="number" class="form-control" name="lunch_duration" id="lunch_duration" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Lecture Duration (After Interval in minutes)<span class="help"></span></label>
                    <div class="col-md-12 ">
                        <input type="number" class="form-control" name="a_slot_duration" id="a_slot_duration" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">No. of Lecture (After Interval)<span class="help"></span></label>
                    <div class="col-md-12 ">
                        <input type="number" class="form-control" name="a_lacture" id="a_lacture" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12">Classes</label>
                    <div class="col-sm-12">
                        <select class="selectpicker form-control" name="classes[]" id="classes" required multiple >
                            <?php
                            foreach ($classes as $c){
                            ?>
                            <option value="<?=$c->classID?>"><?=$c->class?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12">Status</label>
                    <div class="col-sm-12">
                        <select class="form-control" name="is_active" id="is_active" required>
                            <option value="Y">Active</option>
                            <option value="N">InActive</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-success" value="ADD SLOT"> </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->
