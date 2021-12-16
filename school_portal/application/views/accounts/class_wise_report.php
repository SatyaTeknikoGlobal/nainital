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
$total = 0;
$concession = 0;
$payable = 0;
$paid = 0;
$due = 0;
?>

<!-- .row -->
<div class="row">
    <div class="col-sm-12">
        <div class="white-box">
            <h3 class="box-title m-b-0"><?=$title?></h3>
            <div class="table-responsive">
                <table id="example23" class="display table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Total</th>
                        <th>Concession</th>
                        <th>Payable</th>
                        <th>Paid</th>
                        <th>Due</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Total</th>
                        <th>Concession</th>
                        <th>Payable</th>
                        <th>Paid</th>
                        <th>Due</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach ($report as $f){
                        $count++;
                        $total += $f->total_amount;
                        $concession += $f->concession;
                        $payable += $f->payable_amount;
                        $paid += $f->paid_amount;
                        $due += $f->due_amount;
                        ?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$f->class?></td>
                            <td><?=$f->section?></td>
                            <td><?=$f->total_amount?></td>
                            <td><?=$f->concession?></td>
                            <td><?=$f->payable_amount?></td>
                            <td><?=$f->paid_amount?></td>
                            <td><?=$f->due_amount?></td>
                        </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-3 col-sm-offset-1 bg-purple text-center" style="border-radius: 10px;padding: 10px;">
            <h4 style="color: white">Total Amount: Rs. <?=$total?></h4>
            <h4 style="color: white">Total Concession: Rs. <?=$concession?></h4>
            <h4 style="color: white">Total Payable: Rs. <?=$payable?></h4>
            <h4 style="color: white">Amount Received: Rs. <?=$paid?></h4>
            <h3 style="color: white">DUE AMOUNT: Rs. <?=$due?></h3>
        </div>
    </div>
</div>
<!-- /.row -->

