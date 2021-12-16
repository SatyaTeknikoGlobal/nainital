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
            <h3 class="box-title m-b-0">Add POLL</h3>
            <p>  </p>
            <form class="form-horizontal" method="post">
                <?php
                if (isset($message)){
                    echo '<div class="form-group"><div class="col-sm-6 col-sm-offset-2"><h3 class="text-danger"> * '.$message.'</h3></div></div>';
                }
                ?>
                <div class="form-group">
                    <label class="col-md-2">Question * <span class="help"></span></label>
                    <div class="col-md-4" data-placement="bottom" data-align="top" data-autoclose="true">
                        <textarea type="text" class="form-control" name="question" id="question" required></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2">Question Type * <span class="help"></span></label>
                    <div class="col-md-4 ">
                        <select class="form-control" name="ques_type" id="ques_type" required>
                            <option value="open"> OPEN </option>
                            <option value="option"> OPTIONAL </option>
                            <option value="voting"> VOTING </option>
                            <option value="scale"> SCALE </option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2">Start Date * <span class="help"></span></label>
                    <div class="col-md-4" data-placement="bottom" data-align="top" data-autoclose="true">
                        <input class="form-control date_select" name="start_date" id="start_date" placeholder="dd-mm-yyyy" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2">End Date * <span class="help"></span></label>
                    <div class="col-md-4" data-placement="bottom" data-align="top" data-autoclose="true">
                        <input class="form-control date_select" name="end_date" id="end_date" placeholder="dd-mm-yyyy" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2">Status * <span class="help"></span></label>
                    <div class="col-md-4 ">
                        <select class="form-control" name="status" id="status" required>
                            <option value="Y"> AVAILABLE </option>
                            <option value="N"> NOT AVAILABLE </option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2">ALL Teachers<span class="help"></span></label>
                    <div class="col-md-4 ">
                        <input type="checkbox" value="Y" checked="checked" name="allteacher" id="allteacher">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2">ALL Students<span class="help"></span></label>
                    <div class="col-md-4 ">
                        <input type="checkbox" value="Y" checked="checked" name="allstudent" id="allstudent">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2">Classes<span class="help"></span></label>
                    <div class="col-md-4 ">
                        <select class="form-control" name="classesID[]" id="classesID" multiple>
                            <?php
                            foreach ($classes as $c){
                            ?>
                                <option value="<?= $c->classID ?>"> <?= $c->class ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-success" value="Add Poll"> </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->
<script>
    $(document).ready(function () {
        $('.date_select').datepicker({
            format: "dd-mm-yyyy",
        });
    });
</script>