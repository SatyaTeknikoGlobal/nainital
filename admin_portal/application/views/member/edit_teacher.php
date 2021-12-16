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
    <div class="col-sm-8">
        <div class="white-box">
            <h3 class="box-title m-b-0"><?= $title?></h3>
            <p class="text-muted m-b-30 font-13"> Edit Teacher (<?= $teacher_info->name ?>) </p>
            <form class="form-horizontal" method="post" >
                <div class="form-group">
                    <label class="col-sm-4">Name : </label>
                    <label class="col-sm-8"><?= $teacher_info->name ?></label>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Username : </label>
                    <label class="col-sm-8"><?= $teacher_info->username ?></label>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Mobile : <span class="help"></span></label>
                    <div class="col-sm-8">
                        <input type="number" style="font-weight :500" value="<?= $teacher_info->phone ?>" class="form-control" name="phone" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Email : <span class="help"></span></label>
                    <div class="col-sm-8">
                        <input type="email" style="font-weight :500" value="<?= $teacher_info->email ?>" class="form-control" name="email" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Address : <span class="help"></span></label>
                    <div class="col-sm-8">
                    	<textarea class="form-control" name="address" rows="4" style="font-weight: 500" required><?= $teacher_info->address ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Status : </label>
                    <div class="col-sm-8">
                        <select class="form-control" style="font-weight: 500" name="is_active" id="is_active" required>
                            <option <?php if ($teacher_info->is_active == "Y") echo "selected" ;?> value="Y">Active</option>
                            <option <?php if ($teacher_info->is_active == "D") echo "selected" ;?> value="D">Suspend</option>
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
    	<div class="row white-box text-center">
    		<?php
    		if (is_null($teacher_info->image) || $teacher_info->image == ""){
                echo "<img class='img-rounded' height = '200px' src=".base_url('uploads/images').'/default.png'.">";
            }else{
                echo "<img class='img-rounded' height = '200px' src=".base_url('uploads/images').'/'.$teacher_info->image.">";
            }
            ?>
    	</div>
    	<div class="row white-box" style="padding-top: 2px;padding-bottom: 2px;">
    		<h3 class="box-title m-b-0">Reset Password</h3>
    		<p class="text-muted m-b-30 font-13"> Reset Password (<?= $teacher_info->name ?>) </p>
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-12">New Password : </label>
                    <div class="col-md-12">
                        <input type="password" name="new_password" id="new_password" class="form-control"> 
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12">Confirm Password : </label>
                    <div class="col-md-12">
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control"> 
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="button" onclick="reset_psw()" class="btn btn-success" value="Reset"> 
                    </div>
                </div>
            </div>
    	</div>
    </div>
</div>
<!-- /.row -->

<script>
	function reset_psw(){
		var role = "teacher";
		var teacherID = "<?=$teacher_info->teacherID?>";
		var new_password = $("#new_password").val();
		var confirm_password = $("#confirm_password").val();
		if (new_password == "" || confirm_password == "") {
			alert("Please Enter new and confirm password");
		}else if (new_password != confirm_password) {
			alert("New and Confirm Password must be same");
		}else{
			$.ajax({
				url : "<?=base_url("configuration/reset_psw")?>",
				type : "post",
				data : "role="+role+"&id="+teacherID+"&new_password="+new_password+"&confirm_password="+confirm_password,
				success : function(resp){
					if (resp.toUpperCase() == "SUCCESS") {
						$.toast({
                            heading: resp.toUpperCase(),
                            text: 'Password updated successfully.',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 1500,
                            stack: 6
                        });
					}else{
						$.toast({
                            heading: resp.toUpperCase(),
                            text: 'Failed to update password.',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 1500,
                            stack: 6
                        });
					}
				}
			});
		}
	}
</script>
