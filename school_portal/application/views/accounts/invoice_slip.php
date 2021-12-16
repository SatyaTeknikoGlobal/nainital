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
$due = 0;
$paid = 0;
$actual_amount = 0;
$concession = 0;
$payable = 0;
foreach ($fee_heads as $fh){
    $due += $fh->due_amount;
    $paid += $fh->paid_amount;
    $actual_amount += $fh->total_amount;
    $concession += $fh->concession;
    $payable += $fh->payable_amount;
}
?>
<style>
    *{
        word-break: break-all;
    }

</style>
<!-- .row -->
<div class="row" id="print_div">
    <div class="col-xs-12">
        <div class="white-box">
           <div class="row">
               <div class="col-xs-2">
                   <img class="img img-thumbnail" width="100%" src="<?=$logo?>">
               </div>
               <div class="col-xs-6 col-xs-offset-1 text-center">
                   <h3 style="color: #707478"><b><?=strtoupper($school_info->school_name)?></b></h3>
                   <h4 style="color: #707478"><b><?=strtoupper($school_info->address)?></b></h4>
               </div>
               <div class="col-xs-2 col-xs-offset-1 text-right">
                   <h3 style="color: #707478"><b><?=strtoupper("School Copy")?></b></h3>
               </div>
           </div>
           <div class="row m-t-5">
               <div class="col-xs-12">
                   <table class="table table-bordered">
                       <thead>
                       <tr>
                           <th class="col-xs-8">Student Details</th>
                           <th class="col-xs-4">Invoice Details</th>
                       </tr>
                       </thead>
                       <tbody>
                       <tr>
                           <td>
                               <div class="col-xs-6">
                                   <div class="row">
                                       <div class="col-xs-4"><b>Name: </b></div>
                                       <div class="col-xs-8"><span><?=$student_info->name?></span></div>
                                    </div>
                                   <div class="row">
                                       <div class="col-xs-4"><b>Class: </b></div>
                                       <div class="col-xs-8"><span> <?=$student_info->class?></span></div>
                                   </div>
                                   <div class="row">
                                       <div class="col-xs-4"><b>Section: </b></div>
                                       <div class="col-xs-8"><span> <?=$student_info->section?></span></div>
                                   </div>
                                   <div class="row">
                                       <div class="col-xs-4"><b>Roll No: </b></div>
                                       <div class="col-xs-8"><span> <?=$student_info->roll_no?></span></div>
                                   </div>
                               </div>
                               <div class="col-xs-6">
                                   <div class="row">
                                       <div class="col-xs-4"><b>Parent: </b></div>
                                       <div class="col-xs-8"><span><?=$student_info->parent?></span></div>
                                   </div>
                                   <div class="row">
                                       <div class="col-xs-4"><b>Phone: </b></div>
                                       <div class="col-xs-8"><span> <?=$student_info->phone?></span></div>
                                   </div>
                                   <div class="row">
                                       <div class="col-xs-4"><b>Email: </b></div>
                                       <div class="col-xs-8"><span> <?=$student_info->email?></span></div>
                                   </div>
                                   <div class="row">
                                       <div class="col-xs-4"><b>Address: </b></div>
                                       <div class="col-xs-8"><span> <?=$student_info->address?></span></div>
                                   </div>
                               </div>
                           </td>
                           <td>
                               <div class="col-xs-12">
                                   <div class="row">
                                       <div class="col-xs-6"><b>Invoice No: </b></div>
                                       <div class="col-xs-6"><span><?=$invoice->invoiceID?></span></div>
                                   </div>
                                   <div class="row">
                                       <div class="col-xs-6"><b>Invoice Date: </b></div>
                                       <div class="col-xs-6"><span> <?=date("d/m/Y",strtotime($invoice->added_on))?></span></div>
                                   </div>
                                   <div class="row">
                                       <div class="col-xs-6"><b>Actual: </b></div>
                                       <div class="col-xs-6"><span> <?=$actual_amount?></span></div>
                                   </div>
                                   <div class="row">
                                       <div class="col-xs-6"><b>Concession: </b></div>
                                       <div class="col-xs-6"><span> <?=$concession?></span></div>
                                   </div>
                                   <div class="row">
                                       <div class="col-xs-6"><b>Payable: </b></div>
                                       <div class="col-xs-6"><span> <?=$payable?></span></div>
                                   </div>
                                   <div class="row">
                                       <div class="col-xs-6"><b>Paid: </b></div>
                                       <div class="col-xs-6"><span> <?=$paid?></span></div>
                                   </div>
                                   <div class="row">
                                       <div class="col-xs-6"><b>Due: </b></div>
                                       <div class="col-xs-6"><span> <?=$due?></span></div>
                                   </div>
                                   <div class="row">
                                       <div class="col-xs-6"><b>Due Date: </b></div>
                                       <div class="col-xs-6"><span> <?=date("d/m/Y",strtotime($invoice->due_date))?></span></div>
                                   </div>
                               </div>
                           </td>
                       </tr>
                       </tbody>
                   </table>
               </div>
           </div>
            <hr>
            <br>
           <div class="row m-t-5">
                <div class="col-xs-2">
                    <img class="img img-thumbnail" width="100%" src="<?=$logo?>">
                </div>
                <div class="col-xs-6 col-xs-offset-1 text-center">
                    <h3 style="color: #707478"><b><?=strtoupper($school_info->school_name)?></b></h3>
                    <h4 style="color: #707478"><b><?=strtoupper($school_info->address)?></b></h4>
                </div>
                <div class="col-xs-2 col-xs-offset-1 text-right">
                    <h3 style="color: #707478"><b><?=strtoupper("Student Copy")?></b></h3>
                </div>
           </div>
            <div class="row m-t-5">
                <div class="col-xs-12">
                    <table class="table table-bordered m-b-0">
                        <thead>
                        <tr>
                            <th class="col-xs-8">Student Details</th>
                            <th class="col-xs-4">Invoice Details</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <div class="col-xs-6">
                                    <div class="row">
                                        <div class="col-xs-4"><b>Name: </b></div>
                                        <div class="col-xs-8"><span><?=$student_info->name?></span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4"><b>Class: </b></div>
                                        <div class="col-xs-8"><span> <?=$student_info->class?></span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4"><b>Section: </b></div>
                                        <div class="col-xs-8"><span> <?=$student_info->section?></span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4"><b>Roll No: </b></div>
                                        <div class="col-xs-8"><span> <?=$student_info->roll_no?></span></div>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="row">
                                        <div class="col-xs-4"><b>Parent: </b></div>
                                        <div class="col-xs-8"><span><?=$student_info->parent?></span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4"><b>Phone: </b></div>
                                        <div class="col-xs-8"><span> <?=$student_info->phone?></span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4"><b>Email: </b></div>
                                        <div class="col-xs-8"><span> <?=$student_info->email?></span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4"><b>Address: </b></div>
                                        <div class="col-xs-8"><span> <?=$student_info->address?></span></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-6"><b>Invoice No: </b></div>
                                        <div class="col-xs-6"><span><?=$invoice->invoiceID?></span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6"><b>Invoice Date: </b></div>
                                        <div class="col-xs-6"><span> <?=date("d/m/Y",strtotime($invoice->added_on))?></span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6"><b>Actual: </b></div>
                                        <div class="col-xs-6"><span> <?=$actual_amount?></span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6"><b>Concession: </b></div>
                                        <div class="col-xs-6"><span> <?=$concession?></span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6"><b>Payable: </b></div>
                                        <div class="col-xs-6"><span> <?=$payable?></span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6"><b>Paid: </b></div>
                                        <div class="col-xs-6"><span> <?=$paid?></span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6"><b>Due: </b></div>
                                        <div class="col-xs-6"><span> <?=$due?></span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6"><b>Due Date: </b></div>
                                        <div class="col-xs-6"><span> <?=date("d/m/Y",strtotime($invoice->due_date))?></span></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered m-b-0">
                        <thead>
                        <tr>
                            <th class="col-xs-4">Fee Head</th>
                            <th class="col-xs-2 text-center">Actual</th>
                            <th class="col-xs-2 text-center">Concession</th>
                            <th class="col-xs-2 text-center">Payable</th>
                            <th class="col-xs-2 text-center">Due</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($fee_heads as $fa){ ?>
                            <tr>
                                <td><?=strtoupper($fa->feehead)?></td>
                                <td class="text-center"><?=$fa->total_amount?></td>
                                <td class="text-center"><?=$fa->concession?></td>
                                <td class="text-center"><?=$fa->payable_amount?></td>
                                <td class="text-center"><?=$fa->due_amount?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Total</th>
                            <th class="text-center"><?=$actual_amount?></th>
                            <th class="text-center"><?=$concession?></th>
                            <th class="text-center"><?=$payable?></th>
                            <th class="text-center"><?=$due?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->
<script type="text/javascript">
    function printDiv(divName) {
        w=window.open();
        w.document.write($('#print_div').html());
        w.print($('#print_div'));
        w.document.close();
        w.focus();
        w.close();
    }
    jQuery(document).bind("keyup keydown", function(e){
        if(e.ctrlKey && e.keyCode == 80){
            var css = "";
            var myStylesLocation = "<?=base_url("assets")?>/bootstrap/dist/css/bootstrap.min.css";

            $.ajax({
                url: myStylesLocation,
                type: "POST",
                async: false
            }).done(function(data){
                css += data;
            })
            w=window.open();
            w.document.write('<html><head><title><?=$student_info->name?></title>');
            w.document.write('<style type="text/css">'+css+' </style>');
            w.document.write('<style>*{word-break: break-all;}h3{font-size: 18px;}h4{font-size: 16px;}table{font-size: 12px;}</style>');
            w.document.write('</head><body >');
            w.document.write($('#print_div').html());
            w.document.write('</body></html>');
            w.document.close();
            w.focus();
            w.print($('#print_div'));
            w.close();
            return false;
        }
    });

</script>