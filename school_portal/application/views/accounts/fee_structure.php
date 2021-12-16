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
                        <th>Fee Head</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Class</th>
                        <th>Fee Head</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach ($feestructure as $f){
                        $count++;
                        ?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$f->class?></td>
                            <td><?=$f->feehead?></td>
                            <td><?=$f->amount?></td>
                            <td><a data-toggle="modal" data-target="#responsive-modal<?=$f->feestructureID?>" class="btn btn-danger model_img img-responsive"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>

                        <div id="responsive-modal<?=$f->feestructureID?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Update Fee Structure</h4> </div>
                                    <form action="<?=base_url('accounts/fee_structure/edit/').$f->feestructureID?>" method="POST"">
                                    <div class="modal-body">

                                        <div class="form-group">
                                            <label class="col-sm-12">Classes</label>
                                                <select class="form-control" name="classID" required>
                                                    <?php
                                                    foreach ($classes as $c){
                                                        if ($f->classID == $c->classID){
                                                        ?>
                                                        <option selected value="<?=$c->classID?>"><?=$c->class?></option>
                                                    <?php }else{ ?>
                                                            <option value="<?=$c->classID?>"><?=$c->class?></option>
                                                    <?php }}?>
                                                </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-12">Fee Head</label>
                                                <select class="form-control" name="feeheadID" id="feeheadID" required>
                                                    <?php
                                                    foreach ($feehead as $fh){
                                                    if ($f->feeheadID == $fh->feeheadID){
                                                        ?>
                                                        <option selected value="<?=$fh->feeheadID?>"><?=$fh->feehead?></option>
                                                    <?php }else{ ?>
                                                        <option value="<?=$fh->feeheadID?>"><?=$fh->feehead?></option>
                                                    <?php }} ?>
                                                </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Amount<span class="help"></span></label>
                                                <input type=number step=0.01 value="<?= $f->amount ?>" required class="form-control" name="amount"> </div>

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
            <h3 class="box-title m-b-0">Add Fee Structure</h3>
            <p class="text-muted m-b-30 font-13"> (e.g., One Time, Regular, etc) </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-sm-12">Classes</label>
                    <div class="col-sm-12">
                        <select class="selectpicker form-control" name="classID[]" required multiple>
                            <?php
                            foreach ($classes as $c){
                                ?>
                                <option value="<?=$c->classID?>"><?=$c->class?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12">Fee Head</label>
                    <div class="col-sm-12">
                        <select class="form-control" name="feeheadID" id="feeheadID" required>
                            <?php
                            foreach ($feehead as $fh){
                                ?>
                                <option value="<?=$fh->feeheadID?>"><?=$fh->feehead?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Amount<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type=number step=0.01 required class="form-control" name="amount" placeholder="Enter Amount"> </div>
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

