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
                        <th>Subject</th>
                        <th>Topic</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Topic</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach ($topics as $c){
                        $count++;
                        ?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$c->class?></td>
                            <td><?=$c->subject?></td>
                            <td><?=$c->Level_name?></td>
                            <td>
                                <a title="view" href="<?= base_url('quiz/quiz_list/').$c->Level_id ?>" target="_blank" class="btn btn-primary model_img img-responsive"><i class="fa fa-sign-in-alt"></i></a>
                                <a data-toggle="modal" title="edit" data-target="#responsive-modal<?=$c->Level_id?>" class="btn btn-danger model_img img-responsive"><i class="fa fa-edit"></i></a>
                                <a title="delete" href="<?= base_url('quiz/topics/delete/').$c->Level_id ?>" onclick="return confirm('Are you sure you want to delete this?');" class="btn btn-success model_img img-responsive"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>

                        <div id="responsive-modal<?=$c->Level_id?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Update Topic</h4> </div>
                                    <form action="<?=base_url('quiz/topics/edit/').$c->Level_id?>" method="POST"">
                                    <div class="modal-body">

                                        <div class="form-group">
                                            <label for="classid" class="control-label">Class Name:</label>
                                            <select required class="form-control" name="classid" id="classid">
                                                <?php foreach ($classes as $cl){ ?>
                                                    <option value="<?= $cl->classid ?>" <?php if ($cl->classid == $c->classid){ echo "selected"; }?> ><?= $cl->class ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="subjectid" class="control-label">Subject:</label>
                                            <select required class="form-control" name="subjectid" id="subjectid">
                                                <?php foreach ($subject as $s){ ?>
                                                    <option value="<?= $s->subID ?>" <?php if ($s->subID == $c->subjectid){echo "selected";}?> ><?= $s->subject ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="Level_name" class="control-label">Topic:</label>
                                            <input type="text" required class="form-control" name="Level_name" id="Level_name" value="<?=$c->Level_name?>">
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
            <h3 class="box-title m-b-0">Add Topic</h3>
            <p class="text-muted m-b-30 font-13"> Add Topic for Quiz </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-sm-12">Class Name</label>
                    <div class="col-sm-12">
                        <select required class="form-control" name="classid" id="classid">
                            <?php foreach ($classes as $cl){ ?>
                                <option value="<?= $cl->classid ?>"><?= $cl->class ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12">Subject</label>
                    <div class="col-sm-12">
                        <select required class="form-control" name="subjectid" id="subjectid">
                            <?php foreach ($subject as $s){ ?>
                                <option value="<?= $s->subID ?>" ><?= $s->subject ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Topic<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" required class="form-control" name="Level_name" id="Level_name" placeholder="Enter Topic Name">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-success" value="ADD TOPIC"> </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->
