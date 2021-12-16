<?php
/*
| -----------------------------------------------------
| PRODUCT NAME: 	MENTOR ERP
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

<div class="row">
    <!--profile picture-->
    <div class="col-sm-3">
        <div class="white-box">
            <div class="row">
                <img src="<?=$profile->image?>" style="width: 100%" class="img-thumbnail">
            </div>
            <hr>
            <div class="row">
                <label class="col-xs-5 control-label">Name : </label>
                <label class="col-xs-7 controls"><?=$profile->name?></label>
            </div>
            <div class="row">
                <label class="col-xs-5 control-label">Role : </label>
                <label class="col-xs-7 controls"><?=$role?></label>
            </div>
        </div>
    </div>
    <!--User Information-->
    <div class="col-sm-9">
        <div class="white-box">
            <h2 style="margin-top: 10px !important;margin-bottom: 15px !important;font-weight: 500;"><i class="ti-user"></i>&nbsp;&nbsp;ABOUT</h2>
            <div class="row">
                <div class="col-sm-6">
                    <div class="row" style="margin-bottom: 5px !important;">
                        <label class="col-xs-5 control-label">Name : </label>
                        <div class="col-xs-7 controls"><?=$profile->name?></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="row" style="margin-bottom: 5px !important;">
                        <label class="col-xs-5 control-label">User Name : </label>
                        <div class="col-xs-7 controls"><?=$profile->username?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="row" style="margin-bottom: 5px !important;">
                        <label class="col-xs-5 control-label">Phone : </label>
                        <div class="col-xs-7 controls"><?=$profile->phone?></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="row" style="margin-bottom: 5px !important;">
                        <label class="col-xs-5 control-label">Email : </label>
                        <div class="col-xs-7 controls"><?=$profile->email?></div>
                    </div>
                </div>
            </div>

            <hr>
        </div>
    </div>
</div>