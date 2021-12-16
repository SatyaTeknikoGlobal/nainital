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
                        <th>Fee Head</th>
                        <th>IS OneTime</th>
                        <th>IS Transport</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Fee Head</th>
                        <th>IS OneTime</th>
                        <th>IS Transport</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach ($feehead as $f){
                        $count++;
                        ?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$f->feehead?></td>
                            <td><?=$f->is_onetime?></td>
                            <td><?=$f->is_transport?></td>
                            <td><?=$f->is_active?></td>
                            <td><a data-toggle="modal" data-target="#responsive-modal<?=$f->feeheadID?>" class="btn btn-danger model_img img-responsive"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>

                        <div id="responsive-modal<?=$f->feeheadID?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Update Fee Head</h4> </div>
                                    <form action="<?=base_url('accounts/fee_head/edit/').$f->feeheadID?>" method="POST"">
                                    <div class="modal-body">

                                        <div class="form-group">
                                            <label for="class" class="control-label">Fee Head:</label>
                                            <input type="text" required class="form-control" name="feehead" value="<?=$f->feehead?>"> </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="status" class="control-label">IS OneTime:  </label>
                                                <input name="is_onetime" type="checkbox" <?php if ($f->is_onetime == "Y") echo "checked='true'"?>>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="status" class="control-label">IS Transport:  </label>
                                                <input type="checkbox" name="is_transport" <?php if ($f->is_transport == "Y") echo "checked='true'"?>>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="status" class="control-label">Status:</label>
                                            <select class="form-control" name="is_active" required>
                                                <option <?php if ($f->is_active == "Y") echo "selected"?> value="Y">Active</option>
                                                <option <?php if ($f->is_active == "N") echo "selected"?> value="N">InActive</option>
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
            <h3 class="box-title m-b-0">Add Fee Head</h3>
            <p class="text-muted m-b-30 font-13"> (e.g., Tuition Fee, Admission Fee, etc) </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-md-12">Fee Head<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" required class="form-control" name="feehead" id="feehead" placeholder="Enter Fee Head Name"> </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="status" class="control-label">IS OneTime:  </label>
                        <input name="is_onetime" type="checkbox" <?php if ($f->is_onetime == "Y") echo "checked='true'"?>>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="status" class="control-label">IS Transport:  </label>
                        <input type="checkbox" name="is_transport" <?php if ($f->is_transport == "Y") echo "checked='true'"?>>
                    </div>
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
                        <input type="submit" class="btn btn-success" value="ADD"> </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->

