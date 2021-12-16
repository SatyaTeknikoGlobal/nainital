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
    <div class="col-sm-6 col-sm-offset-3">
        <div class="white-box">
            <h3 class="box-title m-b-0"><?=$title?></h3>
            <p class="text-muted m-b-30 font-13"> Select to Generate </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-sm-12">Report Type</label>
                    <div class="col-sm-12">
                        <select class="form-control" name="report" id="report" required>
                            <option value=""> -- SELECT -- </option>
                            <option value="class_wise_report"> Class Wise Report </option>
                            <option value="single_class"> Single Class Wise Report </option>
                            <option value="fee_head_wise"> Fee Head Wise Report </option>
                            <option value="balance_report"> Fee Balance Report </option>
                            <!--<option value="deposit_report"> Daily Deposit Report </option>-->
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12">Session (optional)</label>
                    <div class="col-sm-12">
                        <select class="form-control" name="session" id="session">
                            <option value=""> -- SELECT -- </option>
                            <?php
                                foreach ($session as $s){
                            ?>
                            <option><?=$s->session?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-success" value="Generate"> </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->

