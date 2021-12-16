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
                        <th>Percentage</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Percentage</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach ($concession as $c){
                        $count++;
                        ?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$c->concession?></td>
                            <td><?=$c->percentage.' %'?></td>
                            <td><?=$c->is_active?></td>
                            <td><a data-toggle="modal" data-target="#responsive-modal<?=$c->concessionID?>" class="btn btn-danger model_img img-responsive"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>

                        <div id="responsive-modal<?=$c->concessionID?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Update Concession</h4> </div>
                                    <form action="<?=base_url('accounts/concession/edit/').$c->concessionID?>" method="POST"">
                                    <div class="modal-body">

                                        <div class="form-group">
                                            <label for="class" class="control-label">Name :</label>
                                            <input type="text" required class="form-control" name="concession" value="<?=$c->concession?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-12">Fee Head</label>

                                            <select class="select2 m-b-10 select2-multiple" name="feehead[]" multiple="multiple" data-placeholder="Choose" style="width: 100%;">
                                                <?php
                                                foreach ($feehead as $fh){
                                                    $selected_feehead = explode(",",$c->feehead);
                                                    if(in_array($fh->feeheadID,$selected_feehead)){
                                                        ?>

                                                        <option selected = "selected" value="<?=$fh->feeheadID?>"><?=$fh->feehead?></option>
                                                    <?php }else{ ?>
                                                        <option value="<?=$fh->feeheadID?>"><?=$fh->feehead?></option>
                                                    <?php }}?>
                                            </select>

                                        </div>
                                        <div class="form-group">
                                            <label for="class" class="control-label">Percentage :</label>
                                            <input pattern="[0-9]{1,3}" required class="form-control" name="percentage" value="<?=$c->percentage?>">
                                            <span class="input-group-addon">%</span>
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
            <h3 class="box-title m-b-0">Add Concession</h3>
            <p class="text-muted m-b-30 font-13"> (e.g., Trust, Government, etc) </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-md-12">Name<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" required class="form-control" name="concession" placeholder="Enter Name for concession">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12">Fee Head</label>
                    <div class="col-sm-12">
                        <select class="selectpicker form-control" name="feehead[]" required multiple>
                            <?php
                            foreach ($feehead as $f){
                                ?>
                                <option value="<?=$f->feeheadID?>"><?=$f->feehead?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Percentage<span class="help"></span></label>
                    <div class="col-md-12">
                        <input pattern="[0-9]{1,3}" required class="form-control" name="percentage" placeholder="Enter Percentage for concession (0-100)">
                        <span class="input-group-addon">%</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12">Status</label>
                    <div class="col-sm-12 input-group">
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

