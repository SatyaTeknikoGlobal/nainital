<?php
/**
 * Created by PhpStorm.
 * User: kshit
 * Date: 2019-01-30
 * Time: 13:28:19
 */
?>
<div class="row">
    <div class="col-sm-12 p-10 bg-white">
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                        <h4><b>Name: </b></h4>
                    </div>
                    <div class="col-sm-8">
                        <h4><?=ucwords($student->name)?></h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                        <h4><b>Parent Name: </b></h4>
                    </div>
                    <div class="col-sm-8">
                        <h4><?=ucwords($student->parent)?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                        <h4><b>Class: </b></h4>
                    </div>
                    <div class="col-sm-8">
                        <h4><?=ucwords($student->class." - ".$student->section)?></h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                        <h4><b>Roll No: </b></h4>
                    </div>
                    <div class="col-sm-8">
                        <h4><?=strtoupper($student->roll_no)?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                        <h4><b>Due Amount: </b></h4>
                    </div>
                    <div class="col-sm-8">
                        <h4><?=number_format($total_due,2)?></h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                        <h4><b>Fine: </b></h4>
                    </div>
                    <div class="col-sm-8">
                        <h4><?=number_format($total_fine,2)?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 bg-white p-10 m-t-10">
        <div class="row m-l-10 m-r-10">
            <div class="col-sm-12">
                <h3><b style="float: left">Fee Heads</b><b style="float: right"><a data-toggle="modal" data-target="#responsive-modal" class="btn btn-success">Take Payment</a></b></h3>
            </div>
        </div>
    </div>
    <div class="col-sm-12 bg-white p-10 m-t-10">
        <div class="row m-l-10 m-r-10">
            <table id="invoice_table" class="table table-bordered" style="width: 100%">
                <thead>
                <tr>
                    <th>Session</th>
                    <th>Fee Head</th>
                    <th>Due Date</th>
                    <th>Invoice Total</th>
                    <th>Concession</th>
                    <th>Payable</th>
                    <th>Paid</th>
                    <th>Due</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($fee_head as $i){ ?>
                    <tr>
                        <td><?= $i->session ?></td>
                        <td><?= $i->feehead ?></td>
                        <td><?= date("d M Y",strtotime($i->due_date)) ?></td>
                        <td><?= $i->total_amount ?></td>
                        <td><?= $i->concession ?></td>
                        <td><?= $i->payable_amount ?></td>
                        <td><?= $i->paid_amount ?></td>
                        <td><?= $i->due_amount ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <th>Session</th>
                    <th>Fee Head</th>
                    <th>Due Date</th>
                    <th>Invoice Total</th>
                    <th>Concession</th>
                    <th>Payable</th>
                    <th>Paid</th>
                    <th>Due</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div id="responsive-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Take Payment</h4> </div>
                <form method="POST">
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="due" class="control-label col-sm-4">DUE AMOUNT: </label>
                        <label for="due" id="due" class="control-label col-sm-8"><?=$total_due?></label>
                    </div>
                    <div class="form-group row">
                        <label for="fine" class="control-label col-sm-4">Fine: </label>
                        <label for="fine" id="fine"  class="control-label col-sm-8"><?=$total_fine?></label>
                    </div>
                    <div class="form-group row">
                        <label for="fine" class="control-label col-sm-4">Fine Concession: </label>
                        <div class="col-sm-8">
                            <input type="number" step="2" required class="form-control" name="fine_concession" id="fine_concession" value="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cash" class="control-label col-sm-4">Cash: </label>
                        <div class="col-sm-8">
                            <input type="number" step="2" required class="form-control" name="cash" id="cash" value="<?= ($total_fine + $total_due) ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#invoice_table").DataTable();
    });
</script>
<script>
    $(document).ready(function () {
       $('#fine_concession').change(function () {
          var due = parseFloat($('#due').text());
          var fine = parseFloat($('#fine').text());
          var fine_concession = parseFloat($('#fine_concession').val());
          if (fine_concession <= fine){
              due += (fine - fine_concession);
          }else {
              due += fine;
          }
            $('#cash').val(due);
       });
    });
</script>