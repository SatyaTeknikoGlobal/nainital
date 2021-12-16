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
    <div class="col-sm-8">
        <div class="white-box">
            <h3 class="box-title m-b-0"><?=$title?></h3>


            <div class="table-responsive">
                <table id="example23" class="display nowrap table table-hover table-striped" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Start Year</th>
                        <th>End Year</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Start Year</th>
                        <th>End Year</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach ($batch as $c){
                        $count++;
                        ?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$c->start_year?></td>
                            <td><?=$c->end_year?></td>
                            <td><?=$c->is_active?></td>
                            <td><a data-toggle="modal" data-target="#responsive-modal<?=$c->batchID?>" class="btn btn-danger model_img img-responsive"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>

                        <div id="responsive-modal<?=$c->batchID?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Update Batch</h4> </div>
                                    <form action="<?=base_url('configuration/batch/edit/').$c->batchID?>" method="POST"">
                                    <div class="modal-body">

                                        <div class="form-group">
                                            <label for="class" class="control-label">Start Year:</label>
                                            <input type="text" pattern="[0-9]{4}" disabled required class="form-control year_pick" name="start_year" id="start_year" value="<?=$c->start_year?>"> </div>
                                        <div class="form-group">
                                            <label for="class" class="control-label">End Year:</label>
                                            <input type="text" pattern="[0-9]{4}" disabled required class="form-control year_pick" name="end_year" id="end_year" value="<?=$c->end_year?>"> </div>
                                        <div class="form-group">
                                            <label for="status" class="control-label">Status:</label>
                                            <select class="form-control" name="is_active" id="is_active" required>
                                                <option <?php if ($c->is_active == "Y") echo "selected"?> value="Y">Active</option>
                                                <option <?php if ($c->is_active == "N") echo "selected"?> value="N">InActive</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light">Save changes</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="white-box">
            <h3 class="box-title m-b-0">Add Batch</h3>
            <p class="text-muted m-b-30 font-13"> Add Batch for your School </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-md-12">Start Year<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" pattern="[0-9]{4}" required class="form-control year_pick" name="start_year" id="start_year" placeholder="Enter Start Year"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">End Year<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" required class="form-control year_pick"  pattern="[0-9]{4}" name="end_year" id="end_year" placeholder="Enter End Year"> </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12">Status</label>
                    <div class="col-sm-12">
                        <select class="form-control" name="is_active" id="is_active" required>
                            <option value="Y">Active</option>
                            <option value="N">InActive</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-success" value="ADD Batch"> </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->
<script>
    $(document).ready(function(){
        $('.year_pick').datepicker({
            format: "yyyy",
            startView: "year",
            minViewMode: "years"
        });
    });
</script>