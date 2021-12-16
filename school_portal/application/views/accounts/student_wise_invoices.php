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
                <h3><b style="float: left">INVOICES</b><!--<b style="float: right"><a data-toggle="modal" data-target="#responsive-modal" class="btn btn-success">Take Payment</a></b>--></h3>
            </div>
        </div>
    </div>
    <div class="col-sm-12 bg-white p-10 m-t-10">
        <div class="row m-l-10 m-r-10">
            <table id="invoice_table" class="table table-bordered" style="width: 100%">
                <thead>
                <tr>
                    <th>Session</th>
                    <th>Month Year</th>
                    <th>Due Date</th>
                    <th>Invoice Total</th>
                    <th>Concession</th>
                    <th>Payable</th>
                    <th>Paid</th>
                    <th>Due</th>
                    <th>Fine</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($invoices as $i){ ?>
                    <tr>
                        <td><?= $i['session'] ?></td>
                        <td><?= $i['monthyear'] ?></td>
                        <td><?= date("d M Y",strtotime($i['due_date'])) ?></td>
                        <td><?= $i['total'] ?></td>
                        <td><?= $i['concession'] ?></td>
                        <td><?= $i['payable'] ?></td>
                        <td><?= $i['paid'] ?></td>
                        <td><?= $i['due'] ?></td>
                        <td><?= $i['fine'] ?></td>
                        <td><a href="<?=base_url("accounts/view_slip/").$i['invoiceID'].'/'.md5($i['invoiceID'])?>" target="_blank" class="btn btn-success"><i class='fa fa-eye'></i></a> <?php if($i['due'] > 0){ ?><a href="<?=base_url("accounts/pay/").$i['invoiceID'].'/'.md5($i['invoiceID'])?>" target="_blank" class="btn btn-primary">Pay</a><?php } ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <th>Session</th>
                    <th>Month Year</th>
                    <th>Due Date</th>
                    <th>Invoice Total</th>
                    <th>Concession</th>
                    <th>Payable</th>
                    <th>Paid</th>
                    <th>Due</th>
                    <th>Fine</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
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