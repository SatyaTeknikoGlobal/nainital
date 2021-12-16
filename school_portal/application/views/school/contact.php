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
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach ($contact as $c){
                        $count++;
                        ?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$c->name?></td>
                            <td><?=$c->designation?></td>
                            <td><?=$c->phone?></td>
                            <td><?=$c->email?></td>
                            <td><?=$c->is_active?></td>
                            <td><a data-toggle="modal" data-target="#responsive-modal<?=$c->contactID?>" class="btn btn-danger model_img img-responsive"><i class="fa fa-edit"></i></a></td>
                        </tr>

                        <div id="responsive-modal<?=$c->contactID?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Update Contact</h4> </div>
                                    <form action="<?=base_url('configuration/contact_list/edit/').$c->contactID?>" method="POST"">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="class" class="control-label">Name:</label>
                                            <input type="text" required class="form-control" name="name" value="<?=$c->name?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="class" class="control-label">Designation:</label>
                                            <input type="text" required class="form-control" name="designation" value="<?=$c->designation?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="class" class="control-label">Phone:</label>
                                            <input type="text" required class="form-control" name="phone" value="<?=$c->phone?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="class" class="control-label">Email (Optional):</label>
                                            <input type="email" class="form-control" name="email" value="<?=$c->email?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="status" class="control-label">Status:</label>
                                            <select class="form-control" name="is_active" required>
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
            <h3 class="box-title m-b-0">Add Contact</h3>
            <p class="text-muted m-b-30 font-13"> Add Contact for Contact Directory </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-md-12">Name<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" required class="form-control" name="name" id="name" placeholder="Enter Name">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Designation<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" required class="form-control" name="designation" id="designation" placeholder="Enter Designation">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Phone<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" required class="form-control" name="phone" id="phone" placeholder="Enter Contact Number">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Email (Optional)<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email">
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
                        <input type="submit" class="btn btn-success" value="ADD Contact"> </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->
