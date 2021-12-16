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
    <div class="col-sm-12">
        <div class="white-box">
            <h3 class="box-title m-b-0"><div class="col-xs-10"><p><?=$title?></p></div><div><p><a class="btn btn-sm btn-success text-right" data-toggle="modal" data-target="#responsive-modal-add-new"><i class="fa fa-plus"></i> ADD Expanse</a></p></div></h3>


            <div class="table-responsive">
                <table id="example23" class="display table-bordered table table-hover table-striped" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Expense</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Note</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Expense</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Note</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach ($expense as $f){
                        $count++;
                        ?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$f->expense?></td>
                            <td><?=$f->date?></td>
                            <td><?=$f->amount?></td>
                            <td><?=$f->note?></td>
                            <td>
                                <a data-toggle="modal" data-target="#responsive-modal<?=$f->expenseID?>" class="btn btn-danger model_img img-responsive"><i class="fa fa-edit"></i></a>
                                <a onclick="return window.confirm(this.title || 'Do You Want to Delete this record?');" class="btn btn-primary model_img img-responsive" href="<?=base_url("accounts/expense/delete/").$f->expenseID?>"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>

                        <div id="responsive-modal<?=$f->expenseID?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Update Expense</h4> </div>
                                    <form action="<?=base_url('accounts/expense/edit/').$f->expenseID?>" method="POST"">
                                    <div class="modal-body">

                                        <div class="form-group">
                                            <label for="class" class="control-label">Expense:</label>
                                            <input type="text" required class="form-control" name="expense" value="<?=$f->expense?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="class" class="control-label">Date:</label>
                                            <input type="text" required autocomplete="off" class="form-control datepicker1" name="date" value="<?=$f->date?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="class" class="control-label">Amount:</label>
                                            <input type="text" required class="form-control" name="amount" value="<?=$f->amount?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="class" class="control-label">Note:</label>
                                            <textarea type="text" class="form-control" name="note" rows="3"><?=$f->note?></textarea>
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
    <div id="responsive-modal-add-new" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">ADD Expense</h4> </div>
                <form action="<?=base_url('accounts/expense')?>" method="POST"">
                <div class="modal-body">

                    <div class="form-group">
                        <label for="class" class="control-label">Expense:</label>
                        <input type="text" required class="form-control" name="expense" placeholder="Provide a title to Expense">
                    </div>
                    <div class="form-group">
                        <label for="class " class="control-label">Date:</label>
                        <input type="text" autocomplete="off" required class="form-control datepicker1" name="date" placeholder="YYYY-MM-DD">
                    </div>
                    <div class="form-group">
                        <label for="class" class="control-label">Amount:</label>
                        <input type="number" step="any" required class="form-control" name="amount" placeholder="Enter Amount.">
                    </div>
                    <div class="form-group">
                        <label for="class" class="control-label">Note:</label>
                        <textarea type="text" class="form-control" name="note" rows="3" placeholder="Add a note."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light">ADD</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->

<script>
    $(document).ready(function () {
        $('.datepicker1').datepicker({
            format: 'yyyy-mm-dd',
            container: '.modal'
        });
    });
</script>

