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
                        <th>Class</th>
                        <th>Section</th>
                        <th>Class Teacher</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Class Teacher</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach ($section as $s){
                        $count++;
                        ?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$s->class?></td>
                            <td><?=$s->section?></td>
                            <td><?=$s->name?></td>
                            <td><?=$s->is_active?></td>
                            <td><a data-toggle="modal" data-target="#responsive-modal<?=$s->sectionID?>" class="btn btn-danger model_img img-responsive"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>

                        <div id="responsive-modal<?=$s->sectionID?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Update Section</h4> </div>
                                    <form action="<?=base_url('configuration/section/edit/').$s->sectionID?>" method="POST"">
                                    <div class="modal-body">

                                        <div class="form-group">
                                            <label for="status" class="control-label">Status:</label>
                                            <select class="form-control" name="classID" id="classID" required>
                                                <option value="">--SELECT--</option>
                                                <?php foreach ($classes as $c){ ?>
                                                    <option <?php if ($s->classID == $c->classID) echo "selected"?> value="<?=$c->classID?>"><?=$c->class?></option>
                                                <?php } ?>

                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="class" class="control-label">Section Name:</label>
                                            <input type="text" required class="form-control" name="section" id="section" value="<?=$s->section?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="status" class="control-label">Class Teacher (optional):</label>
                                            <select class="form-control" name="class_teacherID" id="class_teacherID">
                                                <option value="">--SELECT--</option>
                                                <?php foreach ($teacher as $t){ ?>
                                                    <option <?php if ($s->class_teacherID == $t->teacherID) echo "selected"?> value="<?=$t->teacherID?>"><?=$t->name?></option>
                                                <?php } ?>

                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="status" class="control-label">Status:</label>
                                            <select class="form-control" name="is_active" id="is_active" required>
                                                <option <?php if ($s->is_active == "Y") echo "selected"?> value="Y">Active</option>
                                                <option <?php if ($s->is_active == "N") echo "selected"?> value="N">InActive</option>
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
            <h3 class="box-title m-b-0">Add Section</h3>
            <p class="text-muted m-b-30 font-13"> Add Section in classes </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-sm-12">Class</label>
                    <div class="col-sm-12">
                        <select class="form-control" name="classID" id="classID" required>
                            <option value="">--SELECT--</option>
                            <?php foreach ($classes as $c){ ?>
                                <option value="<?=$c->classID?>"><?=$c->class?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Section Name<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" required class="form-control" name="section" id="section" placeholder="Enter Class Name"> </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12">Class Teacher (optional)</label>
                    <div class="col-sm-12">
                        <select class="form-control" name="class_teacherID" id="class_teacherID">
                            <option value="">--SELECT--</option>
                            <?php foreach ($teacher as $t){ ?>
                                <option value="<?=$t->teacherID?>"><?=$t->name?></option>
                            <?php } ?>
                        </select>
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
                        <input type="submit" class="btn btn-success" value="ADD SECTION"> </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->


