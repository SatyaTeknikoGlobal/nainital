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
                        <th>Route</th>
                        <th>Description</th>
                        <th>Default Price</th>
                        <th>School Latlong</th>
                        <th>LastStop Latlong</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Route</th>
                        <th>Description</th>
                        <th>Default Price</th>
                        <th>School Latlong</th>
                        <th>LastStop Latlong</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach ($route as $c){
                        $count++;
                        ?>
                        <tr>
                            <td><?=$count ?></td>
                            <td><?=$c->route ?></td>
                            <td><?=$c->description ?></td>
                            <td><?=$c->price ?></td>
                            <td><?=$c->school_latlong ?></td>
                            <td><?=$c->lastpoint_latlong ?></td>
                            <td><a data-toggle="modal" data-target="#responsive-modal<?=$c->routeID?>" class="btn btn-danger model_img img-responsive"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>

                        <div id="responsive-modal<?=$c->routeID?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Update Route</h4> </div>
                                    <form action="<?=base_url('transport/index/edit/').$c->routeID?>" method="POST"">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="route" class="control-label">Route:</label>
                                                <input type="text" required class="form-control" name="route" id="route" value="<?=$c->route?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="route" class="control-label">Default Price:</label>
                                                <input type="number" step="any" required class="form-control" name="price" id="price" value="<?=$c->price?>">
                                            </div>
                                            <?php
                                                $school = explode(',',$c->school_latlong);
                                                $lastpoint = explode(',',$c->lastpoint_latlong);
                                            ?>
                                            <div class="form-group">
                                                <label for="route" class="control-label">School Latitude:</label>
                                                <input type="number" step="any" required class="form-control" name="s_latitude" id="s_latitude" value="<?=$school[0]?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="route" class="control-label">School Longitude:</label>
                                                <input type="number" step="any" required class="form-control" name="s_longitude" id="s_longitude" value="<?=$school[1]?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="route" class="control-label">LastStop Latitude:</label>
                                                <input type="number" step="any" required class="form-control" name="l_latitude" id="l_latitude" value="<?=$lastpoint[0]?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="route" class="control-label">LastStop Longitude:</label>
                                                <input type="number" step="any" required class="form-control" name="l_longitude" id="l_longitude" value="<?=$lastpoint[1]?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="class" class="control-label">Description:</label>
                                                <textarea required class="form-control" name="description" id="description"><?=$c->description?></textarea>
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
            <h3 class="box-title m-b-0">Add Route</h3>
            <p class="text-muted m-b-30 font-13"> Add Route for Transport </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-md-12">Route : <span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" required class="form-control" name="route" id="route" placeholder="Enter Name For Route (eg; Route 1A)"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Default Price : <span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="number" step="any" required class="form-control" name="price" id="price" placeholder="Enter Default Price"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">School Latitude : <span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="number" step="any" required class="form-control" name="s_latitude" id="s_latitude" placeholder="eg; 20.xxxxx"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">School Longitude : <span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="number" step="any" required class="form-control" name="s_longitude" id="s_longitude" placeholder="eg; 70.xxxxx"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">LastStop Latitude : <span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="number" step="any" required class="form-control" name="l_latitude" id="l_latitude" placeholder="eg; 20.xxxxx"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">LastStop Longitude : <span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="number" step="any" required class="form-control" name="l_longitude" id="l_longitude" placeholder="eg; 70.xxxxx"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Description : <span class="help"></span></label>
                    <div class="col-md-12">
                        <textarea required class="form-control" name="description" id="description" placeholder="Enter Description For Route"></textarea> </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-success" value="ADD ROUTE"> </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->
