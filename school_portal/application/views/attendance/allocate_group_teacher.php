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
                <table id="teacher_table" class="display nowrap table table-hover table-striped" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>TeacherID</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Group</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>TeacherID</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Group</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.row -->
<script>
    $(document).ready(function () {
        $('#teacher_table').DataTable( {
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
                "url": "<?=base_url()?>attendance/list_teacher_group",
                "type": 'GET',
            }
        });
    });
</script>
<script>
    function alert_selected(sel)
    {
        var teacherID = sel;
        var groupID = document.getElementById(sel).value;
        if (teacherID != "" && groupID != "")
        {
            $.ajax({
                url: "<?= base_url("attendance/teacher_allocate_group")?>",
                method: "POST",
                data:"teacherID="+teacherID+"&groupID="+groupID,
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