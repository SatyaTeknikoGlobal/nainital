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
            <h3 class="box-title m-b-0">Add Images</h3>
            <p class="text-muted m-b-30 font-13"> <?=$title?> </p>
            <form class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="col-md-12">Select Image <span class="help"></span></label>

                    <div class="col-md-12">
                        <input type="file" multiple required accept="image/*" class="form-control" name="images[]" id="images" placeholder="Enter Gallery Name">
                        <p class="text-muted font-13" style="margin: 0">(Max 10 Image at a time  for better performance)<br> Each image should not be greater than 2 MB</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-12">Images For</label>
                    <div class="col-sm-12">
                        <span>Teachers</span>
                        <input type="checkbox" checked name="teacher">&nbsp;&nbsp;&nbsp;
                        <span>Students</span>
                        <input type="checkbox" checked name="student">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-success" value="ADD Images"> </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->
