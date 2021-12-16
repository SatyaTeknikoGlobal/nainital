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
            <h3 class="box-title m-b-0">Update Scale</h3>
            <p>  </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-md-2">Scale1<span class="help"></span></label>
                    <div class="col-md-4" data-placement="bottom" data-align="top" data-autoclose="true">
                        <input type="text" class="form-control" name="scale1" id="scale1" value="<?=$scale['scale1']?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2">Scale2<span class="help"></span></label>
                    <div class="col-md-4 ">
                        <input type="text" class="form-control" name="scale2" value="<?=$scale['scale2']?>" id="scale2" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-success" value="Update Scale"> </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->
