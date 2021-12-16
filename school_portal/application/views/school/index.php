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
if ($is_config == "Y"){
?>
<!-- .row -->
<div class="row">
    <div class="col-sm-8">
        <div class="white-box">
            <h3 class="box-title m-b-0">School Configuration</h3>
            <p class="text-muted m-b-30 font-13"> Update configuration </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-md-12">School Name<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" required class="form-control" name="school_name" id="school_name" value="<?=$configuration->school_name?>"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">School Prefix<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" class="form-control" required name="school_prefix" id="school_prefix" value="<?=$configuration->school_prefix?>"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Academic Year<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" class="form-control" required name="default_academic_year" id="default_academic_year" value="<?=$configuration->default_academic_year?>"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Principal Name<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="text" class="form-control" required name="principal_name" id="principal_name" value="<?=$configuration->principal_name?>"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">School Email<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="email" class="form-control" required name="email" id="email" value="<?=$configuration->email?>"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">School Phone<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="number" class="form-control" name="phone" id="phone" value="<?=$configuration->phone?>"> </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12">Default Attendance</label>
                    <div class="col-sm-12">
                        <select class="form-control" name="default_attendance" id="default_attendance" required>
                            <option  <?php if ($configuration->default_attendance != "daywise" && $configuration->default_attendance != "subject" || $configuration->default_attendance == ""){echo "selected";}?> value="">-- SELECT --</option>
                            <option  <?php if ($configuration->default_attendance == "daywise"){echo "selected";}?> value="daywise">Day Wise Attendance</option>
                            <option <?php if ($configuration->default_attendance == "subject"){echo "selected";}?> value="subject">Subject Wise Attendance</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Per Day Fine<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="number" class="form-control" name="per_day_fine" id="per_day_fine" value="<?=$configuration->per_day_fine?>"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Fee Fine Amount<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="number" class="form-control" name="fees_fine_amt" id="fees_fine_amt" value="<?=$configuration->fees_fine_amt?>"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Fee Fine Type<span class="help"></span></label>
                    <div class="col-md-12">
                        <select class="form-control" name="fees_fine_config" id="fees_fine_config" required>
                            <option value="">-- SELECT --</option>
                            <option  <?php if ($configuration->fees_fine_config == "day"){echo "selected";}?> value="day">Daily</option>
                            <option <?php if ($configuration->fees_fine_config == "month"){echo "selected";}?> value="month">Monthly</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-success" value="UPDATE"> </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="white-box">
            <h3 class="box-title m-b-0">School Logo</h3>
            <img class="img img-thumbnail" width="100%" src="<?php if (is_null($configuration->logo) || $configuration->logo == ""){echo base_url("uploads/logo/erplogo.png");}else{echo base_url("uploads/logo/$configuration->logo");}?>">
            <hr>
            <form class="form-horizontal" enctype="multipart/form-data" action="<?=base_url("configuration/update_logo")?>" method="post">
                <div class="form-group">
                    <label class="col-md-12">Change Logo<span class="help"></span></label>
                    <div class="col-md-12">
                        <input type="file" required class="form-control" name="logo" id="logo" accept="image/png">
                        <p class="font-13">Note: Upload .png file with dimension (200px X 200px) </p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-success" value="UPDATE LOGO"> </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.row -->
<?php }else{?>
    <!-- .row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title m-b-0">Please Configure Your School from APP . After that you will be able to update configuration.</h3>

            </div>
        </div>

    </div>
    <!-- /.row -->
<?php }?>
