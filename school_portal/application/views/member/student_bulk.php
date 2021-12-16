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
            <h3 class="box-title m-b-30"><?= $title ?> (CSV)   <a class="btn btn-sm btn-primary" href="<?=base_url("sample/students.csv")?>">Sample Download</a></h3>
            <form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?=base_url("members/student_bulk_import_section_wise/")?>">
                <div class="form-group">
                    <label class="col-sm-12">Batch (Optional) : </label>
                    <div class="col-sm-12">
                        <select class="form-control" style="width: 100%" name="batchID" id="batchID">
                            <option value="">--SELECT--</option>
                            <?php foreach ($batch as $b){ ?>
                                <option value="<?=$b->batchID?>"><?=$b->start_year." - ".$b->end_year?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12">Class : </label>
                    <div class="col-sm-12">
                        <select class="form-control" style="width: 100%" name="classID" id="classID" required>
                            <option value="">--SELECT--</option>
                            <?php foreach ($classes as $c){ ?>
                                <option value="<?=$c->classID?>"><?=$c->class?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12">SECTION : </label>
                    <div class="col-sm-12">
                        <select class="form-control" style="width: 100%" name="sectionID" id="sectionID" required>

                        </select>
                    </div>
                </div>
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
<script>
    $('document').ready(function () {
        $('#classID').change(function () {
            var classID = $(this).val();
            var data = "classID="+classID;
            if (classID != ''){
                $.ajax({
                    url: "<?= base_url("configuration/get_sectionbyclass")?>",
                    method: "POST",
                    data:data,
                    success:function(res)
                    {
                        var i, x = "";
                        var myObj = JSON.parse(res);
                        x += "<option value=''>--select--</option>";
                        for (i in myObj) {

                            x += "<option value='" + myObj[i].sectionID + "'>" + myObj[i].section + "</option>";

                        }

                        document.getElementById("sectionID").innerHTML = x;
                    }
                });
            }
        });
    });
</script>
