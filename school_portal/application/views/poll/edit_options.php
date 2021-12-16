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
            <h3 class="box-title m-b-0">Update Option</h3>
            <p>  </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-md-2">Option1<span class="help"></span></label>
                    <div class="col-md-4" data-placement="bottom" data-align="top" data-autoclose="true">
                        <input type="text" class="form-control" name="option1" id="option1" value="<?=$options['option1']?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2">Option2<span class="help"></span></label>
                    <div class="col-md-4 ">
                        <input type="text" class="form-control" name="option2" value="<?=$options['option2']?>" id="option2" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2">Option3<span class="help"></span></label>
                    <div class="col-md-4" data-placement="bottom" data-align="top" data-autoclose="true">
                        <input type="text" class="form-control" name="option3" id="option3" value="<?=$options['option3']?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2">Option4<span class="help"></span></label>
                    <div class="col-md-4 ">
                        <input type="text" class="form-control" name="option4" value="<?=$options['option4']?>" id="option4" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-success" value="Update Option"> </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->
