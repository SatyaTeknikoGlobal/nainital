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
            <h3 class="box-title m-b-0">Generate Invoice</h3>
            <p class="text-muted m-b-30 font-13"> Generate Invoice </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-sm-12">Fee Type<span class="help"></span></label>
                    <div class="col-sm-12">
                        <select class="form-control" name="feetypeID" id="feetypeID" required>
                            <option value="">--SELECT--</option>
                            <?php foreach ($fee_type as $ft){ ?>
                                <option value="<?=$ft->feetypeID?>"><?=$ft->feetype?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">From Month<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" required class="form-control month-year" name="from_month-year" id="from_month-year" placeholder="select month year (eg; January 2019) ">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">To Month<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" required class="form-control month-year" name="to_month-year" id="to_month-year" placeholder="select month year (eg; February 2019) ">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12">Classes</label>
                    <div class="col-sm-12">
                        <select class="selectpicker form-control" name="classID[]" id="classID" multiple required>
                            <option value="">--SELECT--</option>
                            <?php foreach ($classes as $c){ ?>
                                <option value="<?=$c->classID?>"><?=$c->class?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12">Due Date</label>
                    <div class="col-sm-12">
                        <input type="text" required class="form-control" name="last_date" id="last_date" placeholder="select last date for fee payment">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-success" value="Generate" id="submit_btn"> </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->
<script>
    $(document).ready(function(){
        $('.month-year').datepicker({
             format: "MM yyyy",
            startView: "year", 
            minViewMode: "months"
        }).on('changeDate', function(e){
            $(this).datepicker('hide');
        });
        $('#last_date').datepicker({
            format: "dd-mm-yyyy",
        }).on('changeDate', function(e){
            $(this).datepicker('hide');
        });
    });
</script>

