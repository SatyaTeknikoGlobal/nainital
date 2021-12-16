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
                    <label class="col-sm-12">Class (optional) : </label>
                    <div class="col-sm-12">
                        <select class="form-control classID" style="width: 100%" onchange="get_subject(this)" name="classID" id="classID" required>
                            <option value="0">--SELECT--</option>
                            <?php foreach ($classes as $c){ ?>
                                <option value="<?=$c->classID?>"><?=$c->class?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label class="col-sm-12">Subject (optional) : </label>
                    <div class="col-sm-12">
                        <select class="form-control" style="width: 100%" name="subjectID" id="subjectID" required>
                            <option value="0"> -- SELECT -- </option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label class="col-sm-12">Category (optional) : </label>
                    <div class="col-sm-12">
                        <select class="form-control" style="width: 100%" name="categoryID" id="categoryID" required>
                            <option value="0">--SELECT--</option>
                            <?php foreach ($category as $c){ ?>
                                <option value="<?=$c->bookcatID?>"><?=$c->category?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label class="col-sm-12"><p></p></label>
                    <div class="col-md-12">
                        <button class="btn btn-success" onclick="get_books(event)"> SUBMIT </button>
                    </div>
                    <p id="demo"></p>
                </div>
            </div>
            <h3>&nbsp; </h3>
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
                <table id="books_table" class="display table table-hover table-striped" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Book</th>
                        <th>Subject</th>
                        <th>Category</th>
                        <th>ISBN No</th>
                        <th>Author</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Remaining</th>
                        <th>Rack</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Book</th>
                        <th>Subject</th>
                        <th>Category</th>
                        <th>ISBN No</th>
                        <th>Author</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Remaining</th>
                        <th>Rack</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.row -->
<script>
    function get_subject(classID) {
        var classID = classID.value;
        if (classID != ""){
            $.ajax({
               url : "<?=base_url("library/get_subject")?>",
               data : "classID="+classID,
               method : "post",
               success : function (res) {
                   var res = JSON.parse(res);
                   var option = "<option value='0'> -- SELECT -- </option>";
                   for (var i in res){
                       option += "<option value='"+res[i]['subjectID']+"'>"+res[i]['subject_name']+" ("+res[i]['subject_code']+") "+"</option>";
                   }
                   $('#subjectID').html(option);
               }
            });
        }
    }
</script>
<script>
    function get_all_books() {
        $("#books_table").DataTable().destroy();
        $('#books_table').DataTable( {
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
                "url": "<?=base_url()?>library/book_list/",
                "type": 'GET',
            }
        });
    }
    function get_books(e) {
        e.preventDefault();
        var classID = $('#classID').val();
        var subjectID = $('#subjectID').val();
        var categoryID = $('#categoryID').val();
        if (classID == "0" && subjectID == "0" && categoryID == "0"){
            get_all_books();
        } else {
            $("#books_table").DataTable().destroy();
            $('#books_table').DataTable( {
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
                    "url": "<?=base_url()?>library/book_list/"+classID+"/"+subjectID+"/"+categoryID+"/",
                    "type": 'GET',
                }
            });
        }
    }
</script>
<script>
    $(document).ready(function () {
        get_all_books();
    });
</script>