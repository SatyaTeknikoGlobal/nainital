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
            <h3 class="box-title m-b-0"><div class="col-xs-10"><p><?=$title?></p></div><div><p><a class="btn btn-sm btn-success text-right" data-toggle="modal" data-target="#responsive-modal-add-new"><i class="fa fa-plus"></i> ADD Holiday</a></p></div></h3>


            <div class="table-responsive">
                <table id="example23" class="display table-bordered table table-hover table-striped" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Holiday</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Holiday</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach ($holidays as $f){
                        $count++;
                        ?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$f->title?></td>
                            <td><?=date("d M Y",strtotime($f->date))?></td>
                            <td><?=$f->description?></td>
                            <td><?=$f->status?></td>
                            <td>
                                <a data-toggle="modal" data-target="#responsive-modal<?=$f->holidayID?>" class="btn btn-danger model_img img-responsive"><i class="fa fa-edit"></i></a>
                                <a onclick="return window.confirm(this.title || 'Do You Want to Delete this record?');" class="btn btn-primary model_img img-responsive" href="<?=base_url("configuration/holiday/delete/").$f->holidayID?>"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>

                        <div id="responsive-modal<?=$f->holidayID?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Update Holiday</h4> </div>
                                    <form action="<?=base_url('configuration/holiday/edit/').$f->holidayID?>" method="POST"">
                                    <div class="modal-body">

                                        <div class="form-group">
                                            <label for="title" class="control-label">Title:</label>
                                            <input type="text" required class="form-control" name="title" value="<?=$f->title?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="date" class="control-label">Date:</label>
                                            <input type="text" required autocomplete="off" class="form-control datepicker1" name="date" value="<?=$f->date?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="description" class="control-label">Description:</label>
                                            <textarea type="text" required class="form-control" name="description"><?=$f->description?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="description" class="control-label">Status:</label>
                                            <select class="form-control" name="status">
                                                <option <?php if ($f->status == 'Y'){echo "selected";}?> value="Y">Y</option>
                                                <option <?php if ($f->status == 'N'){echo "selected";}?> value="N">N</option>
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
    <div id="responsive-modal-add-new" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">ADD Holiday</h4> </div>
                <form action="<?=base_url('configuration/holiday')?>" method="POST"">
                <div class="modal-body">

                    <div class="form-group">
                        <label for="title" class="control-label">Title:</label>
                        <input type="text" required class="form-control" name="title" placeholder="Enter Holiday Title">
                    </div>
                    <div class="form-group">
                        <label for="date" class="control-label">From Date:</label>
                        <input type="text" required autocomplete="off" class="form-control datepicker1" name="from" placeholder="YYYY-MM-DD">
                    </div>
                    <div class="form-group">
                        <label for="date" class="control-label">To Date:</label>
                        <input type="text" required autocomplete="off" class="form-control datepicker1" name="to" placeholder="YYYY-MM-DD">
                    </div>
                    <div class="form-group">
                        <label for="description" class="control-label">Description:</label>
                        <textarea type="text" required class="form-control" name="description" placeholder="Enter Holiday Description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="description" class="control-label">Status:</label>
                        <select class="form-control" name="status">
                            <option value="Y">Y</option>
                            <option value="N">N</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light">ADD</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->

<script>
    $(document).ready(function () {
        $('.datepicker1').datepicker({
            format: 'yyyy-mm-dd',
            container: '.modal'
        }).on('changeDate', function(e){
            $(this).datepicker('hide');
        });
    });
</script>

