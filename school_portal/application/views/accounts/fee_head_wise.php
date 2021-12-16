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
            <h3 class="box-title m-b-0">SELECT Fee Head AND Date</h3>
            <p class="text-muted m-b-30 font-13"> SELECT Fee Heads AND Date AND CLICK ON Generate </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-sm-12">FEE HEAD : </label>
                    <div class="col-sm-12">
                        <select class="select2 form-control" style="width: 100%" name="feeheadID[]" id="feeheadID" multiple required>
                            <?php foreach ($select_fee_head as $c){ ?>
                                <option value="<?=$c->feeheadID?>"><?=$c->feehead?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12">FROM : </label>
                    <div class="col-sm-12">
                        <input type="text" class="datepicker1 form-control" name="date_from" id="date_from" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12">TO : </label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control datepicker1" name="date_to" id="date_to" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <button class="btn btn-success" type="submit"> Generate </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->
<script>
    $(document).ready(function () {
        $('.datepicker1').datepicker({
            format: 'yyyy-mm-dd'
        });
    });
</script>