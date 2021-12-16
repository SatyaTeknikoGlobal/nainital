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
    <div class="col-sm-8 col-sm-offset-2">
        <div class="white-box">
            <h3 class="box-title m-b-0">Edit Exam</h3>
            <p class="text-muted m-b-30 font-13"> ( e.g., Quarterly, Half Yearly, Yearly ) </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-md-12">Class <span class="red_text">*</span></label>
                    <div class="col-md-12 input-group">
                        <input type="text" required disabled class="form-control" name="exam_class" id="exam_class" att="<?=$exam->classID?>" placeholder="e.g., Quarterly, Half Yearly, Yearly " value="<?=$exam->class?>"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Exam Title <span class="red_text">*</span></label>
                    <div class="col-md-12 input-group">
                        <input type="text" required class="form-control" name="exam_title" id="exam_title" placeholder="e.g., Quarterly, Half Yearly, Yearly " value="<?=$exam->exam_title?>"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Exam Note or Description <span class="red_text">*</span></label>
                    <div class="col-md-12 input-group">
                        <textarea name="exam_desc" id="exam_desc" class="form-control" required placeholder="Exam Details" rows="4"><?=$exam->exam_note?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Exam Date <span class="red_text">*</span></label>
                    <div class="col-sm-12 input-group date" id="datepicker1">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        <input type="text" required class="form-control" name="exam_date" id="exam_date" value="<?=$exam->exam_date?>" placeholder="e.g., 2018-01-20 ">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.row -->
<script>
    $(document).ready(function () {
        $('#datepicker1').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '-3d'
        });
    });
</script>

