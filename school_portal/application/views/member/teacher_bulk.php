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
    <div class="col-sm-6 col-sm-offset-3">
        <div class="white-box">
            <h3 class="box-title m-b-30"><?= $title ?> (CSV)   <a class="btn btn-sm btn-primary" href="<?=base_url("sample/teachers.csv")?>">Sample Download</a></h3>
            <form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?=base_url("members/teacher_bulk_import_csv/")?>">
                <div class="form-group">
                    <label class="col-sm-12">CSV File : </label>
                    <div class="col-sm-12">
                        <input class="form-control" type="file" name="imported_file" required accept=".csv">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <button class="btn btn-success" type="submit"> Import </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->
