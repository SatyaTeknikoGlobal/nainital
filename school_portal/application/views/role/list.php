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
                        <th>Role</th>
                        <th>Added_on</th>
                        <th>Modified_on</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Role</th>
                        <th>Added_on</th>
                        <th>Modified_on</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach ($roles as $r){
                        $count++;
                        ?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$r->role?></td>
                            <td><?=date("d-M-Y H:i:s",strtotime($r->added_on))?></td>
                            <td><?=date("d-M-Y H:i:s",strtotime($r->modified_on))?></td>
                            <td><a data-toggle="modal" data-target="#responsive-modal<?=$r->roleID?>" class="btn btn-danger model_img img-responsive"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>

                        <div id="responsive-modal<?=$r->roleID?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Update Role</h4> </div>
                                    <form action="<?=base_url('members/role_non_teaching/edit/').$r->roleID?>" method="POST">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="class" class="control-label">Role:</label>
                                            <input type="text" required class="form-control" name="role" value="<?=$r->role?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="class" class="control-label">Role:</label>
                                            <input type="text" required class="form-control" name="role123" value="<?=$r->role?>">
                                        </div>
                                        <div class="form-group">
                                            <?php $module_array = explode(",",$r->modules); ?>
                                            <label for="status" class="control-label">Permissions (optional):</label>
                                            <select class="form-control" name="permission[]" MULTIPLE>
                                                <?php foreach ($modules as $m){ ?>
                                                    <option value="<?=$m->moduleID?>" <?php if (in_array($m->moduleID,$module_array)){echo "selected";} ?>><?=$m->name?></option>
                                                <?php } ?>
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
            <h3 class="box-title m-b-0">Add Role </h3>
            <p class="text-muted m-b-30 font-13"> Add Role For Non Teaching Staff </p>
            <form class="form-horizontal" action="<?=base_url("members/role_non_teaching/add")?>" method="post">
                <div class="form-group">
                    <label class="col-md-12">Role<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" required class="form-control" name="role" placeholder="Enter a Name for Role"> </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12">Permissions (optional)</label>
                    <div class="col-sm-12">
                        <select class="form-control selectpicker" name="permission[]" MULTIPLE>
                            <?php foreach ($modules as $m){ ?>
                                <option value="<?=$m->moduleID?>"><?=$m->name?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-success" value="ADD ROLE"> </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->


