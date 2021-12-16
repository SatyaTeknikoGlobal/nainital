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
            <h3 class="box-title m-b-0"><?=$title?></h3>


            <div class="table-responsive">
                <table id="student_table" class="display table table-hover table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="col-sm-1">IMAGE</th>
                        <th class="col-sm-1">School Code</th>
                        <th class="col-sm-1">Student Code</th>
                        <th class="col-sm-1">Name</th>
                        <th class="col-sm-1">Class</th>
                        <th class="col-sm-1">Section</th>
                        <th class="col-sm-1">Phone</th>
                        <th class="col-sm-1">Registered On</th>
                        <th class="col-sm-1">Status</th>
                        <th class="col-sm-1">Subs. Status</th>
                        <th class="col-sm-1">Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>IMAGE</th>
                        <th>School Code</th>
                        <th>School Name</th>
                        <th>Admin Name</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Phone</th>
                        <th>Registered On</th>
                        <th>Status</th>
                        <th>Subs. Status</th>
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
    $('document').ready(function () {
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
                "url": "<?=base_url()?>student/list_student",
                "type": 'GET',
            }
        });
    });
</script>