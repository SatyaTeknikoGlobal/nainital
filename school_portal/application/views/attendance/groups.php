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
                        <th>Group Name</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Created On</th>
                        <th>Modified On</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Group Name</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Created On</th>
                        <th>Modified On</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach ($groups as $c){
                        $count++;
                        ?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$c->group_name?></td>
                            <td><?=$c->latitude?></td>
                            <td><?=$c->longitude?></td>
                            <td><?=date("jS M Y",strtotime($c->added_on))?></td>
                            <td><?=date("jS M Y",strtotime($c->modified_on))?></td>
                            <td><a data-toggle="modal" data-target="#responsive-modal<?=$c->groupID?>" class="btn btn-danger model_img img-responsive"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>

                        <div id="responsive-modal<?=$c->groupID?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Update Group</h4> </div>
                                    <form action="<?=base_url('attendance/groups/edit/').$c->groupID?>" method="POST"">
                                    <div class="modal-body">

                                        <div class="form-group">
                                            <label for="class" class="control-label">Group Name:</label>
                                            <input type="text" required class="form-control" name="group_name" id="group_name" value="<?=$c->group_name?>"> </div>
                                        <div class="form-group">
                                            <label for="class" class="control-label">Latitude:</label>
                                            <input type="number" step="any" required class="form-control" name="latitude" id="latitude" value="<?=$c->latitude?>"> </div>
                                        <div class="form-group">
                                            <label for="class" class="control-label">Longitude:</label>
                                            <input type="number" step="any" required class="form-control" name="longitude" id="longitude" value="<?=$c->longitude?>"> </div>

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
            <h3 class="box-title m-b-0">Add Group</h3>
            <p class="text-muted m-b-30 font-13"> Add Group for your School </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-md-12">Group Name<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" required class="form-control" name="group_name" id="group_name" placeholder="Enter Group Name"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Latitude<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="number" step="any" required class="form-control" name="latitude" id="latitude" placeholder="Enter Latitude"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Longitude<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="number" step="any" required class="form-control" name="longitude" id="longitude" placeholder="Enter Longitude"> </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-success" value="ADD Group"> </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->
