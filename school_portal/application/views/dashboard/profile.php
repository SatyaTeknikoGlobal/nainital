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
            <div class="row">
                <div class="col-sm-6">
                    <div class="row" style="margin-bottom: 5px !important;">
                        <label class="col-xs-5 control-label">Address : </label>
                        <div class="col-xs-7 controls"><?= str_replace(",",",<br>",$profile->address)?></div>
                    </div>
                </div>

            </div>
            <hr>
            <h2 style="margin-top: 10px !important;margin-bottom: 15px !important;font-weight: 500;"><i class="ti-briefcase"></i>&nbsp;&nbsp;SCHOOL</h2>
            <div class="row">
                <div class="col-sm-6">
                    <div class="row" style="margin-bottom: 5px !important;">
                        <label class="col-xs-5 control-label">School Name : </label>
                        <div class="col-xs-7 controls"><?=$profile->school_name?></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="row" style="margin-bottom: 5px !important;">
                        <label class="col-xs-5 control-label">School Code : </label>
                        <div class="col-xs-7 controls"><?=$profile->school_code?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="row" style="margin-bottom: 5px !important;">
                        <label class="col-xs-5 control-label">School Prefix : </label>
                        <div class="col-xs-7 controls"><?=$profile->school_prefix?></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="row" style="margin-bottom: 5px !important;">
                        <label class="col-xs-5 control-label">Principal : </label>
                        <div class="col-xs-7 controls"><?=$profile->principal_name?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="row" style="margin-bottom: 5px !important;">
                        <label class="col-xs-5 control-label">Email : </label>
                        <div class="col-xs-7 controls"><?=$profile->school_email?></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="row" style="margin-bottom: 5px !important;">
                        <label class="col-xs-5 control-label">Phone : </label>
                        <div class="col-xs-7 controls"><?=$profile->school_phone?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="row" style="margin-bottom: 5px !important;">
                        <label class="col-xs-5 control-label">Address : </label>
                        <div class="col-xs-7 controls"><?= str_replace(",",",<br>",$profile->school_address)?></div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-sm-6">
            <div class="white-box">
                <h4 class="page-title" style="font-weight: bold">Change Password&nbsp;&nbsp;</h4>
                <?php if (isset($alert)){echo "<p class='alert-danger'>$alert</p>";}?>
                <?php if (isset($success)){echo "<p class='alert-success'>$success</p>";}?>
                <form class="form-horizontal" method="post">
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="password" name="current_password" placeholder="Current Password" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="password" name="new_password" placeholder="New Password" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="submit" class="btn btn-success" value="Change">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="white-box">
                <h4 class="page-title" style="font-weight: bold">Change Username&nbsp;&nbsp;</h4>
                <span id="change_success" class='alert-success'></span>
                <form class="form-horizontal" method="post">
                    <div class="form-group">
                        <div class="col-xs-10">
                            <input type="text" id="new_username" placeholder="Enter New Username" class="form-control">
                        </div>
                        <div class="col-xs-2">
                            <a onclick="validate_username(event)" class="btn btn-danger form-control" style="color: white"><span class="glyphicon glyphicon-check"></span></a>
                        </div>
                    </div>
                    <p id="validation"></p>
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="submit" onclick="username_change(event)" class="btn btn-success" value="Change">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function validate_username(e) {
        var new_username = $('#new_username').val();
        if (new_username == ""){
            $('#validation').text("! Please Enter a Username");
            $('#validation').css('color', '#473F97');
        } else {
            $.ajax({
                method : "post",
                url : "<?= base_url() .'dashboard/check_username/' ?>",
                data:"username="+new_username,
                success:function (res) {
                    if (res == "success"){
                        $('#validation').text("Available");
                        $('#validation').css('color', '#af293a');
                    } else {
                        $('#validation').text("! Not Available");
                        $('#validation').css('color', '#473F97');
                    }
                }
            });
        }
    }
    function username_change(e) {
        e.preventDefault();
        var new_username = $('#new_username').val();
        if (new_username == ""){
            $('#validation').text("! Please Enter a Username");
            $('#validation').css('color', '#473F97');
        } else {
            $.ajax({
                method : "post",
                url : "<?= base_url() .'dashboard/change_username/' ?>",
                data:"username="+new_username,
                success:function (res) {
                    if (res == "failure"){
                        $('#change_success').text("! Username has been occupied .");
                    } else {
                        $('#change_success').text(res);
                    }
                }
            });
        }
    }
</script>