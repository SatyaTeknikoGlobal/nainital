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
    <div class="col-sm-4">
        <div class="white-box">
            <h3 class="box-title m-b-0">Add Exam</h3>
            <p class="text-muted m-b-30 font-13"> ( e.g., Quarterly, Half Yearly, Yearly ) </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-md-12">Exam Title <span class="red_text">*</span></label>
                    <div class="col-md-12 input-group">
                        <input type="text" required class="form-control" name="exam_title" id="exam_title" placeholder="e.g., Quarterly, Half Yearly, Yearly "> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Exam Note or Description <span class="red_text">*</span></label>
                    <div class="col-md-12 input-group">
                        <textarea name="exam_desc" id="exam_desc" class="form-control" required placeholder="Exam Details" rows="4"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Exam Date <span class="red_text">*</span></label>
                    <div class="col-sm-12 input-group date" id="datepicker1">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        <input type="text" required class="form-control" name="exam_date" id="exam_date" placeholder="e.g., 2018-01-20 ">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12">Classes <span class="red_text">*</span></label>
                    <div class="col-sm-12 input-group">
                        <select class="selectpicker form-control" name="classes[]" id="classes" required multiple >
                            <?php
                            foreach ($classes as $c){
                                ?>
                                <option value="<?=$c->classID?>"><?=$c->class?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="white-box">
            <h3 class="box-title m-b-0"><?=$title?></h3>
            <div class="table-responsive">
                <table id="exam_table" class="display table table-hover table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="col-sm-1">#</th>
                        <th class="col-sm-2">Exam Title</th>
                        <th class="col-sm-2">Exam Description</th>
                        <th class="col-sm-1">Exam Date</th>
                        <th class="col-sm-1">Class</th>
                        <th class="col-sm-2">Result</th>
                        <th class="col-sm-3">Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Exam Title</th>
                        <th>Exam Description</th>
                        <th>Exam Date</th>
                        <th>Class</th>
                        <th>Result</th>
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
    $(document).ready(function () {
        $("#exam_table").DataTable().destroy();
        var t = $('#exam_table').DataTable( {
            dom: 'lfrtip',
            "columnDefs": [
                {
                "searchable": false,
                "orderable": false,
                "targets": 0
                }
            ],
            "lengthMenu": [[10, 25, -1], [10, 25, "All"]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?=base_url()?>exam/list_exam",
                "type": 'GET',
            },
        });
        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    });
</script>
<script>
    $(document).ready(function () {
       $('#datepicker1').datepicker({
           format: 'yyyy-mm-dd',
           startDate: '-3d'
       });
    });
</script>

