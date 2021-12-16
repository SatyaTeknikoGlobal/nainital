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
            <div class="form-inline">
                <div class="col-sm-3">
                    <label class="col-sm-12">Batch (optional) : </label>
                    <div class="col-sm-12">
                        <select class="form-control" style="width: 100%" name="batchID" id="batchID" required>
                            <option value="">--SELECT--</option>
                            <?php foreach ($batch as $c){ ?>
                                <option value="<?=$c->batchID?>"><?=$c->batch?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label class="col-sm-12">Class (optional) : </label>
                    <div class="col-sm-12">
                        <select class="form-control" style="width: 100%" name="classID" id="classID" required>
                            <option value="">--SELECT--</option>
                            <?php foreach ($classes as $c){ ?>
                                <option value="<?=$c->classID?>"><?=$c->class?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label class="col-sm-12">SECTION (optional) : </label>
                    <div class="col-sm-12">
                        <select class="form-control" style="width: 100%" name="sectionID" id="sectionID" required>


                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label class="col-sm-12"><p></p></label>
                    <div class="col-md-12">
                        <button class="btn btn-success" onclick="select_student1()"> SELECT STUDENT </button>
                    </div>
                    <p id="demo"></p>
                </div>
            </div>
            <p>&nbsp;</p>
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
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Batch</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Roll</th>
                        <th>Parent</th>
                        <th>Phone</th>
                        <th>Amount</th>
                        <th>Route</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Batch</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Roll</th>
                        <th>Parent</th>
                        <th>Phone</th>
                        <th>Amount</th>
                        <th>Route</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.row -->
<script>
    function select_student1() {
        var batchID = $('#batchID').val();
        var classID = $('#classID').val();
        var sectionID = $('#sectionID').val();
        if (batchID == ""){
            batchID = null;
        }
        if (classID == "")
        {
            classID = null;
        }
        if (sectionID == "")
        {
            sectionID = null;
        }
        $("#student_table").DataTable().destroy();
        var t = $('#student_table').DataTable( {
            dom: 'lfrtip',
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "lengthMenu": [[10, 25, -1], [10, 25, "All"]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?=base_url()?>transport/list_students/"+classID+"/"+sectionID+"/"+batchID,
                "type": 'GET',
            }
        });
    }

</script>
<script>
    $(document).ready(function () {
        $("#student_table").DataTable().destroy();
        var t = $('#student_table').DataTable( {
            dom: 'lfrtip',
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "lengthMenu": [[10, 25, -1], [10, 25, "All"]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?=base_url()?>transport/list_students/",
                "type": 'GET',
            }
        });
    });
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

<script>
    function alert_selected(sel)
    {
        var studentID = sel;
        var routeID = document.getElementById(sel).value;
        if ( routeID != "")
        {
            $.ajax({
                url: "<?= base_url("transport/get_route_info")?>",
                method: "POST",
                data:"&routeID="+routeID,
                success:function(res){
                   var route = JSON.parse(res);
                   $('#price'+sel).val(route['price']);
                }
            });
        }
    }
    function update_route(sel)
    {
        var studentID = sel;
        var routeID = document.getElementById(sel).value;
        var price = document.getElementById('price'+sel).value;
        if (studentID != "" && routeID != "" && price != "")
        {
            $.ajax({
                url: "<?= base_url("transport/transport_allocation")?>",
                method: "POST",
                data:"studentID="+studentID+"&routeID="+routeID+"&price="+price,
                success:function(res){
                    if(res.toUpperCase() == "SUCCESS"){
                        $.toast({
                            heading: res.toUpperCase(),
                            text: 'Updated Successfully.',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 1500,
                            stack: 6
                        });
                    }else{
                        $.toast({
                            heading: res.toUpperCase(),
                            text: 'Failed to Update.',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 1500,
                            stack: 6
                        });
                    }

                }
            });
        }
    }
</script>