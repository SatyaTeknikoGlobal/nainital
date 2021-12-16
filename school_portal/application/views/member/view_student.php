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
    <div class="col-sm-8">
        <div class="white-box">
            <h3 class="box-title m-b-0"><?= $title?></h3>
            <p class="text-muted m-b-30 font-13"> STUDENT (<?= $student_info->name ?>) </p>
            <div class="form-horizontal" method="post" >
                <div class="form-group">
                    <label class="col-sm-4">Name : </label>
                    <label class="col-sm-8"><?= $student_info->name ?></label>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Parent : </label>
                    <label class="col-sm-8"><?= $student_info->parent ?></label>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Username : </label>
                    <label class="col-sm-8"><?= $student_info->username ?></label>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Email : <span class="help"></span></label>
                    <label class="col-sm-8"><?= $student_info->email ?></label>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Class : </label>
                    <label class="col-sm-8"><?= $student_info->batch ?></label>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Class : </label>
                    <label class="col-sm-8"><?= $student_info->class ?></label>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Section : </label>
                    <label class="col-sm-8"><?= $student_info->section ?></label>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Roll No. : <span class="help"></span></label>
                    <label class="col-sm-8"><?= $student_info->roll_no ?></label>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Status : </label>
                    <label class="col-sm-8"><?php if ($student_info->is_active == "Y") {echo "Active" ;}elseif ($student_info->is_active == "D"){echo "Suspended";} ?></label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
    	<div class="row white-box text-center">
    		<?php
    		if (is_null($student_info->image) || $student_info->image == ""){
                echo "<img class='img-rounded' height = '200px' src=".base_url('uploads/images').'/default.png'.">";
            }else{
                echo "<img class='img-rounded' height = '200px' src=".base_url('uploads/images').'/'.$student_info->image.">";
            }
            ?>
    	</div>
    </div>
</div>
<!-- /.row -->
