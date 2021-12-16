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
    <div class="col-sm-12">
        <div class="white-box">
            <h3 class="box-title m-b-0">SELECT CLASS AND SECTION</h3>
            <p class="text-muted m-b-30 font-13"> SELECT CLASS AND SECTION AND CLICK ON VIEW </p>
            <div class="form-inline">
                <div class="col-sm-5">
                    <label class="col-sm-3">Class : </label>
                    <div class="col-sm-8">
                        <select class="form-control" style="width: 100%" name="classID" id="classID" required>
                            <option value="">--SELECT--</option>
                            <?php foreach ($classes as $c){ ?>
                                <option value="<?=$c->classID?>"><?=$c->class?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-5">
                    <label class="col-sm-3">SECTION : </label>
                    <div class="col-sm-8">
                        <select class="form-control" style="width: 100%" name="sectionID" id="sectionID" required>


                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="col-md-12">
                        <button class="btn btn-success" onclick="select_student()"> SELECT STUDENT </button>
                    </div>
                    <p id="demo"></p>
                </div>
            </div>
            <h3>&nbsp;</h3>
        </div>
    </div>

</div>
<!-- /.row -->
<!-- .row -->
<div class="row">
    <div class="col-sm-12">
        <div class="white-box">
            <h3 class="box-title m-b-0"><?=$title?></h3>
            <div class="table-responsive">
                <table id="student_table" class="display nowrap table table-hover table-striped" cellspacing="0" width="100%">

                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->
<script>
    function select_student() {
        var classID = $('#classID').val();
        var sectionID = $('#sectionID').val();
        var data = "classID="+classID+"&sectionID="+sectionID;
        if (classID == "" || sectionID == "")
        {
            alert("Please Select Class and Section");
        }else {
            $.ajax({
                url: "<?= base_url("configuration/get_students")?>",
                method: "POST",
                data:data,
                success:function(res)
                {
                    var myObj = JSON.parse(res);
                    var i;
                    var students = '<thead><tr><th>#</th><th>Image</th><th>Name</th><th>Roll</th><th>Parent</th><th>Action</th></tr></thead><tfoot><tr><th>#</th><th>Image</th><th>Name</th><th>Roll</th><th>Parent</th><th>Action</th></tr></tfoot><tbody>';
                    var count = 0;
                    for (i in myObj)
                    {
                        count++;
                        var image;
                        if (!myObj[i]['image']) {
                            image = "default.png";
                        }else{
                            image = myObj[i]['image'];
                        }
                        var link = "<?php echo base_url('uploads/images/');?>";
                        var link1 = "<?php echo base_url('exam/result_view/');?>"+myObj[i]['studentID'];
                        students += '<tr><td>'+count+'</td><td><img class="img-rounded" alt="'+myObj[i]['name']+'" height="70px" src="'+link+image+'"></td><td>'+myObj[i]['name']+'</td><td>'+myObj[i]['roll_no']+'</td><td>'+myObj[i]['parent']+'</td><td><a class="btn btn-success" href = "'+link1+'" target="_blank">View Result</a></td></tr>';
                    }
                    students += '</tbody>';
                    $("#student_table").html(students);
                    $("#student_table").DataTable().destroy();
                    $("#student_table").DataTable();
                }
            });
        }
    }
</script>
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