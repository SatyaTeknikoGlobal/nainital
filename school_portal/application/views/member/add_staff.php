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
    <div class="col-sm-8 col-sm-offset-2">
        <div class="white-box">
            <h3 class="box-title m-b-0"><?= $title?></h3>
            <p class="text-muted m-b-30 font-13"> ADD Non Teaching Staff </p>
            <p class="text-blue col-sm-8 col-sm-offset-2" style="font-weight: bold">Note : All Fields are Required</p>
            <h4 class="form_submit_error text-danger col-sm-8 col-sm-offset-2" style="font-weight: bold"></h4>
            <form class="form-horizontal" method="post" >
                <div class="form-group">
                    <label class="col-sm-4">Name * : </label>
                    <div class="col-sm-8">
                        <input type="text" style="font-weight :500" class="form-control required" name="name" id="name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Username/ID * : </label>
                    <div class="col-sm-8">
                        <div class="col-xs-10">
                            <input type="text" id="new_username" name="username" class="form-control required">
                        </div>
                        <div class="col-xs-2">
                            <a onclick="validate_username(event)" class="btn btn-danger form-control" style="color: white"><span class="glyphicon glyphicon-check"></span></a>
                        </div>
                        <p id="validation"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Password * : </label>
                    <div class="col-sm-8">
                        <input type="password" style="font-weight :500" class="form-control required" name="password" id="password" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Confirm Password * : </label>
                    <div class="col-sm-8">
                        <input type="password" style="font-weight :500" class="form-control required" name="confirm" id="confirm" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Mobile * : <span class="help"></span></label>
                    <div class="col-sm-8">
                        <input type="number" style="font-weight :500" class="form-control required" name="phone" id="phone" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Email * : <span class="help"></span></label>
                    <div class="col-sm-8">
                        <input type="email" style="font-weight :500" class="form-control required" name="email" id="email" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Address * : <span class="help"></span></label>
                    <div class="col-sm-8">
                        <textarea class="form-control required" name="address" id="address" rows="4" style="font-weight: 500" required></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Authorize Role * : <span class="help"></span></label>
                    <div class="col-sm-8">
                        <select class="form-control required" required id="roleID" name="roleID">
                            <option value=""> -- SELECT -- </option>
                            <?php
                            foreach ($roles as $r){
                                echo "<option value='".$r->roleID."'>$r->role</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Status * : </label>
                    <div class="col-sm-8">
                        <select class="form-control required" style="font-weight: 500" name="is_active" id="is_active" required>
                            <option value="Y">Active</option>
                            <option value="N">InActive</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" onclick="add_staff(event)" class="btn btn-success" value="ADD">
                        <h4 class="form_submit_error text-danger" style="font-weight: bold"></h4>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.row -->

<script>
    function validate_username(e) {
        var new_username = $('#new_username').val();
        if (new_username == ""){
            $('#validation').text("! Please Enter a Username");
            $('#validation').css('color', '#473F97');
        } else {
            $.ajax({
                method : "post",
                url : "<?= base_url() .'members/check_staff_username/' ?>",
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
</script>
<script>
    function add_staff(e) {
        e.preventDefault();
        var name = $('#name').val();
        var username = $('#new_username').val();
        var password = $('#password').val();
        var confirm = $('#confirm').val();
        var phone = $('#phone').val();
        var email = $('#email').val();
        var address = $('#address').val();
        var roleID = $('#roleID').val();
        var status = $('#is_active').val();
        var phone_filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (name == "" || username == "" || password == "" || confirm == "" || phone == "" || email == "" || address == "" || roleID == "" || status == ""){
            $('.form_submit_error').text(' * Please Fill All The Required Fields');
        } else if(password != confirm){
            $('.form_submit_error').text(' * Password and Confirm Password Not Match');
        }else if(!regex.test(email)){
            $('.form_submit_error').text(' * Please Enter a Valid Email (abc@gmail.com)');
        }else if(!phone_filter.test(phone) && phone.length !=10){
            $('.form_submit_error').text(' * Please Enter a Valid Mobile Number (10 Digits)');
        }else{
            var data = "name="+name+"&username="+username+"&password="+password+"&phone="+phone+"&email="+email+"&address="+address+"&roleID="+roleID+"&is_active="+status;
            $.ajax({
                url : "<?=base_url("members/non_teaching/add")?>",
                method : "post",
                data : data,
                success : function (res) {
                    if (res == "success"){
                        window.location.href = "<?=base_url("members/non_teaching")?>";
                    } else{
                        $('.form_submit_error').text(res);
                    }
                }
            });
        }
    }
</script>
