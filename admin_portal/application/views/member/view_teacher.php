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
            <p class="text-muted m-b-30 font-13">(<?= $teacher_info->name ?>) </p>
            <div class="form-horizontal" method="post" >
                <div class="form-group">
                    <label class="col-sm-4">Name : </label>
                    <label class="col-sm-8"><?= $teacher_info->name ?></label>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Username : </label>
                    <label class="col-sm-8"><?= $teacher_info->username ?></label>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Mobile : <span class="help"></span></label>
                    <label class="col-sm-8"><?= $teacher_info->phone ?></label>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Email : <span class="help"></span></label>
                    <label class="col-sm-8"><?= $teacher_info->email ?></label>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Address : <span class="help"></span></label>
                    <div class="col-sm-8">
                    	<textarea class="form-control" disabled name="address" rows="4" style="font-weight: 500" ><?= $teacher_info->address ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
    	<div class="row white-box text-center">
    		<?php
    		if (is_null($teacher_info->image) || $teacher_info->image == ""){
                echo "<img class='img-rounded' height = '200px' src=".base_url('uploads/images').'/default.png'.">";
            }else{
                echo "<img class='img-rounded' height = '200px' src=".base_url('uploads/images').'/'.$teacher_info->image.">";
            }
            ?>
    	</div>
    </div>
</div>
<!-- /.row -->
