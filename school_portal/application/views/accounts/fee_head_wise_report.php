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
$total = 0.00;
$concession = 0.00;
$payable = 0.00;
$paid = 0.00;
$due = 0.00;
?>
<div class="row">
    <div class="col-sm-12">
        <div class="white-box">
            <h3 class="box-title m-b-0"><?=$title?></h3>
            <div class="table-responsive">
                <table id="example23" class="display table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Fee Head</th>
                        <th>Total Amount</th>
                        <th>Concession</th>
                        <th>Payable</th>
                        <th>Paid</th>
                        <th>Due</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Fee Head</th>
                        <th>Total Amount</th>
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
                        $total += $f->total;
                        $concession += $f->concession;
                        $payable += $f->payable;
                        $paid += $f->paid;
                        $due += $f->due;
                        ?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$f->feehead?></td>
                            <td><?=$f->total?></td>
                            <td><?=$f->concession?></td>
                            <td><?=$f->payable?></td>
                            <td><?=$f->paid?></td>
                            <td><?=$f->due?></td>
                        </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-4 col-sm-offset-1 bg-purple text-center" style="border-radius: 10px;padding: 10px;">
            <h3 style="color: white">TOTAL AMOUNT: Rs. <?=$total?></h3>
            <h3 style="color: white">CONCESSION: Rs. <?=$concession?></h3>
            <h3 style="color: white">PAYABLE AMOUNT: Rs. <?=$payable?></h3>
            <h3 style="color: white">PAID AMOUNT: Rs. <?=$paid?></h3>
            <h3 style="color: white">DUE AMOUNT: Rs. <?=$due?></h3>
        </div>
    </div>
</div>
