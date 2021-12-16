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
                        <th>Category</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach ($category as $f){
                        $count++;
                        ?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$f->category?></td>
                            <td><?=$f->is_active?></td>
                            <td><a data-toggle="modal" data-target="#responsive-modal<?=$f->bookcatID?>" class="btn btn-danger model_img img-responsive"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>

                        <div id="responsive-modal<?=$f->bookcatID?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Update Book Category</h4> </div>
                                    <form action="<?=base_url('library/index/edit/').$f->bookcatID?>" method="POST"">
                                    <div class="modal-body">

                                        <div class="form-group">
                                            <label for="class" class="control-label">Category:</label>
                                            <input type="text" required class="form-control" name="category" value="<?=$f->category?>"> </div>
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
            <h3 class="box-title m-b-0">Add Book Category</h3>
            <p class="text-muted m-b-30 font-13"> (e.g., Science, Mathematic, etc) </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-md-12">Book Category<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" required class="form-control" name="category" id="category" placeholder="Enter Category Name"> </div>
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

