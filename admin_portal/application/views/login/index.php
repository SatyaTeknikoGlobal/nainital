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
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$title?></title>
    
   <!--  <link rel="manifest" href="<?=base_url1("assets/favicon")?>/manifest.json"> -->
    <meta name="msapplication-TileColor" content="#ffffff">
    <!-- <meta name="msapplication-TileImage" content="<?=base_url1("assets/favicon")?>/apple-icon-144x144.png"> -->
    <meta name="theme-color" content="#ffffff">
    <link type="text/css" rel="stylesheet" href="<?=base_url1("assets/bootstrap/dist/css/bootstrap.min.css")?>" >
    <script type="text/javascript" src="<?=base_url1("assets/plugins/bower_components/jquery/dist/jquery.min.js")?>"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .login-box{
            box-shadow: 0 0 5px 5px #e1e0e0;
            padding-top: 15px;
            border: 1px solid #fff;
            border-top: 0;
            margin: 10px;
        }
        .login-box-icon{
            overflow: hidden;
            margin-bottom: 5px;
        }
        .login-logo{
            margin: 0 0 0 10px;
        }
        .login-label{
            background-image: url("<?=base_url1("assets/plugins/images/loginname-bg.png")?>");
            color : #fff;
            z-index: 10;
            position: relative;
            width: 111px;
            height: 54px;
            float: left;
            font-size: 16px;
            margin-left: 10px;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body style="background: #f3f2f2">
    <div class="container-fluid" style="background: #473F97; color: #fff">
        <div class="container text-center">
            <div class="logo" style="display: inline-block; float: left">
                <img src="<?=base_url1("assets/plugins/images/erplogo.png")?>" style="padding-top: 30px;">
            </div>
            <div style="display: inline-block;">
                <h1 style="display: inline-block; line-height: 90px">LOGIN</h1>
            </div>
        </div>
    </div>
    <div class="container-fluid" style="background: #f3f2f2">
        <div class="container">
            <div class="col-md-6">
                <div class="login-box">
                    <div class="login-box-icon">
                        <div class="login-logo">
                            <img src="<?=base_url1("assets/plugins/images/logo_new.png")?>" style="display: block;margin-left: auto;margin-right: auto;" alt="">
                        </div>
                    </div>
                    <div class="login-form">
                        <form class="form-horizontal" method="post">
                            <div class="form-group">
                                <div class="login-label" style="display: inline-block">
                                    <label style="line-height: 40px">Username</label>
                                </div>
                                <div class="col-sm-9" >
                                    <input class="form-control" id="username" style="height: 40px; font-size: 18px">
                                    <span id="usernamespan" style="color: #473F97"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="login-label" style="display: inline-block">
                                    <label style="line-height: 40px">Password</label>
                                </div>
                                <div class="col-sm-9" >
                                    <input type="password" class="form-control" id="password" style="height: 40px; font-size: 18px">
                                    <span id="passwordspan" style="color: #473F97"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-1" >
                                    <input type="submit" class="form-control btn" onclick="do_login(event)" style="background: #473F97; color: #fff; height: 40px; font-size: 18px" value="Sign In">
                                    <span id="loginspan"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="imp-note">
                    <div class="imp-note-left">
                        <h2>Important Note:</h2>
                        <ol style="font-size: 18px">
                            <li>You can login and change Password for your privacy</li>

                            <li>In case of any enquiry, support or problem you can mail us at&nbsp;<br>
                                <a href = "mailto: support@nainitalerp.com"><span id="lblEmailId">support@nainitalerp.com</span></a>&nbsp;</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
    </div>

<script type="text/javascript" src="<?=base_url1("assets/bootstrap/dist/js/bootstrap.min.js")?>"></script>

<script>
    // $(document).ready(function () {
        $("#login").click(function () {
            var username = $("#username").val();
            var password = $("#password").val();

            if(username == ''){
                document.getElementById("username").style.borderColor = "red";
                document.getElementById("usernamespan").innerHTML = "* Please enter username";
            }else{
                document.getElementById("username").style.borderColor = "#ccc";
                document.getElementById("usernamespan").innerHTML = "";
            }
            if(password == ''){
                document.getElementById("password").style.borderColor = "red";
                document.getElementById("passwordspan").innerHTML = "* Please enter password";
            }else{
                document.getElementById("password").style.borderColor = "#ccc";
                document.getElementById("passwordspan").innerHTML = "";
            }
            if(username != '' || password != ''){
                var data = "&username="+username+"&password="+password;
                $.ajax({
                    method : "post",
                    url : "<?= base_url() .'login/check_login/' ?>",
                    data:data,
                    success:function (msg) {
                        if(msg == "Success"){
                            document.getElementById("loginspan").style.color = "green";
                            document.getElementById("loginspan").innerHTML = "Login Successfull ! <br> Redirecting...";
                            location.reload();
                        }else{
                            document.getElementById("loginspan").style.color = "#473F97";
                            document.getElementById("loginspan").innerHTML = "* Please Enter valid credentials !";
                        }
                    }
                });
            }
        });
    // });

</script>
<script>




    function do_login(e) {
        e.preventDefault();
        var username = $("#username").val();
        var password = $("#password").val();

        if(username == ''){
            document.getElementById("username").style.borderColor = "red";
            document.getElementById("usernamespan").innerHTML = "* Please enter username";
        }else{
            document.getElementById("username").style.borderColor = "#ccc";
            document.getElementById("usernamespan").innerHTML = "";
        }
        if(password == ''){
            document.getElementById("password").style.borderColor = "red";
            document.getElementById("passwordspan").innerHTML = "* Please enter password";
        }else{
            document.getElementById("password").style.borderColor = "#ccc";
            document.getElementById("passwordspan").innerHTML = "";
        }
        if(username != '' || password != ''){
            var data = "&username="+username+"&password="+password;

            $.ajax({
                method : "post",
                url : "<?= base_url() .'login/check_login/' ?>",
                data:data,
                success:function (msg) {
                    if(msg == "Success"){
                        document.getElementById("loginspan").style.color = "green";
                        document.getElementById("loginspan").innerHTML = "Login Successfull ! <br> Redirecting...";
                        location.reload();
                    }else{
                        document.getElementById("loginspan").style.color = "#473F97";
                        document.getElementById("loginspan").innerHTML = "* Please Enter valid credentials !";
                    }
                }
            });
        }
    }
</script>
</body>
</html>