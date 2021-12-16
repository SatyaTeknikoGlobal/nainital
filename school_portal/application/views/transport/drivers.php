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
if (array_key_exists('error',$_SESSION)){
    if ($_SESSION['error'] == 'error_msg'){
        $error_msg = $_SESSION['error_msg'];
        unset($_SESSION['error']);
        unset($_SESSION['error_msg']);
    }elseif ($_SESSION['error'] == 'error_msg1'){
        $error_msg1 = $_SESSION['error_msg'];
        unset($_SESSION['error']);
        unset($_SESSION['error_msg']);
    }
}
?>

<!-- .row -->
<div class="row">
    <div class="col-sm-8">
        <div class="white-box">
            <h3 class="box-title m-b-0"><?=$title?> <?php if (isset($error_msg1)){$error_msg1 = urldecode($error_msg1); echo "<p class='m-l-30 text-danger'> $error_msg1 </p>";}?></h3>
            <div class="table-responsive">
                <table id="example23" class="display table-bordered table table-hover table-striped" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Driver</th>
                        <th>Phone/Username</th>
                        <th>Email</th>
                        <th>Routes</th>
                        <th>License No</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Image</th>
                        <th>Driver</th>
                        <th>Phone/Username</th>
                        <th>Email</th>
                        <th>Routes</th>
                        <th>License No</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    foreach ($drivers as $c){
                        $routes = array();
                        foreach ($c['routes'] as $r){
                            $routes[] = $r->route;
                        }
                        $routeID = array();
                        foreach ($c['routes'] as $r){
                            array_push($routeID,$r->routeID);
                        }
                        $routes = implode(',',$routes);
                        ?>
                        <tr>
                            <td><img class="img img-thumbnail" height="70px" width="70px" src="<?= $c['driver_image'] ?>" alt="image"></td>
                            <td><?=$c['driver_name'] ?></td>
                            <td><?=$c['driver_mobile'] ?></td>
                            <td><?=$c['email'] ?></td>
                            <td><?=$routes ?></td>
                            <td><?=$c['license_no'] ?></td>
                            <td><?=$c['status'] ?></td>
                            <td><a data-toggle="modal" data-target="#responsive-modal<?=$c['driverID']?>" title="Edit Driver" class="btn btn-sm btn-danger model_img img-responsive"><i class="fa fa-edit"></i></a>
                                <a data-toggle="modal" data-target="#responsive-modal-psw<?=$c['driverID']?>" title="Change Password" class="btn btn-sm btn-primary model_img img-responsive"><i class="fa fa-key"></i></a>
                                <a data-toggle="modal" data-target="#responsive-modal-user<?=$c['driverID']?>" title="Change Username" class="btn btn-sm btn-warning model_img img-responsive"><i class="fa fa-user"></i></a>
                            </td>
                        </tr>

                        <div id="responsive-modal<?=$c['driverID']?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Update Driver</h4> </div>
                                    <form action="<?=base_url('transport/drivers/edit/').$c['driverID']?>" method="POST"">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="name" class="control-label">Name:</label>
                                                <input type="text" required class="form-control" name="name" id="name" value="<?=$c['driver_name']?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="mobile" class="control-label">Mobile/Username:</label>
                                                <input type="number" disabled class="form-control" name="mobile" id="mobile" value="<?=$c['driver_mobile']?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="email" class="control-label">Email:</label>
                                                <input type="email" class="form-control" name="email" id="email" value="<?=$c['email']?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="license_no" class="control-label">License No:</label>
                                                <input type="text" class="form-control" name="license_no" id="license_no" value="<?=$c['license_no']?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="route" class="control-label">Routes:</label>
                                                <select class="form-control" name="routes[]" id="routes" multiple>
                                                    <?php foreach ($school_routes as $sr){ ?>
                                                    <option <?php if (in_array($sr->routeID,$routeID)){echo "selected";}?> value="<?= $sr->routeID ?>"><?= $sr->route ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="status" class="control-label">Status:</label>
                                                <select class="form-control" name="status" id="status">
                                                    <option <?php if ($c['status'] == "Y"){echo "selected";} ?> value="Y">Active</option>
                                                    <option <?php if ($c['status'] == "N"){echo "selected";} ?> value="N">InActive</option>
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

                        <div id="responsive-modal-psw<?=$c['driverID']?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Change Password</h4> </div>
                                    <form action="<?=base_url('transport/change_password/').$c['driverID']?>" method="POST"">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="password" class="control-label">Password:</label>
                                            <input type="password" required class="form-control" name="password" id="password">
                                        </div>
                                        <div class="form-group">
                                            <label for="c_password" class="control-label">Confirm Password:</label>
                                            <input type="password" required class="form-control" name="c_password" id="c_password" >
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light">Change</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div id="responsive-modal-user<?=$c['driverID']?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Update Username / Mobile</h4> </div>
                                    <form action="<?=base_url('transport/change_username/').$c['driverID']?>" method="POST"">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="mobile" class="control-label">Mobile/Username:</label>
                                            <input type="number" required class="form-control" name="mobile" id="mobile" value="<?=$c['driver_mobile']?>">
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
            <h3 class="box-title m-b-0">Add Driver</h3>
            <p class="text-muted m-b-5 font-13"> Add Driver for Transport </p>
            <?php if (isset($error_msg)){
                $error_msg = urldecode($error_msg);
                echo "<p class='text-danger'> $error_msg </p>";
            }?>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-md-12">Name : <span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" required class="form-control" name="name" id="name" placeholder="Enter Driver Name"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Mobile/Username : <span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="number" required class="form-control" name="mobile" id="mobile" placeholder="eg; 1234567890"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Email : <span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="email" class="form-control" name="email" id="email" placeholder="eg; driver@driver.com"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">License No : <span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" class="form-control" name="license_no" id="license_no" placeholder="eg; abc123"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Password : <span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="password" class="form-control" name="password" id="password" placeholder="eg; abc123"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Routes : <span class="help"></span></label>
                    <div class="col-md-12">
                        <select class="form-control" name="routes[]" id="routes" multiple>
                            <?php foreach ($school_routes as $sr){ ?>
                                <option value="<?= $sr->routeID ?>"><?= $sr->route ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Status : <span class="help"></span></label>
                    <div class="col-md-12">
                        <select class="form-control" name="status" id="status">
                            <option value="Y">Active</option>
                            <option value="N">InActive</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-success" value="ADD DRIVER"> </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->
