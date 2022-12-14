<?php
include("config/db_con.php");
$object = new srms();

?>
<!DOCTYPE html>
<html lang="en">


<head>
    <title>관리자페이지 로그인</title>
    <!--[if lt IE 10]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="CodedThemes" />
    <link rel="icon" href="https://codedthemes.com/demos/admin-templates/datta-able/bootstrap/assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/plugins/animation/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-content subscribe">
        <div class="card">
            <div class="row no-gutters">
                <div class="col-md-4 col-lg-6 d-none d-md-flex d-lg-flex theme-bg align-items-center justify-content-center">
                    <img src="assets/images/user/lock.png" alt="lock images" class="img-fluid">
                </div>
                <div class="col-md-8 col-lg-6">
                    <div class="card-body text-center">
                        <div class="row justify-content-center">
                            <form method="post" id="login_form">
                                <div class="col-sm-12">
                                    <h3 class="mb-4">Login</h3>
                                    <span id="error"></span>
                                    <div class="input-group mb-3">
                                        <input type="text" name="ac_id" id="ac_id" class="form-control" placeholder="ID">
                                    </div>
                                    <div class="input-group mb-4">
                                        <input type="password" name="ac_passwd" id="ac_passwd" class="form-control" placeholder="password">
                                    </div>
                                    <button name="login_button" id="login_button"  class="btn btn-primary shadow-2 mb-4">Login</button>
                                    <!--<p class="mb-2 text-muted">Forgot password? <a href="auth-reset-password-v4.html">Reset</a></p>-->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script src="assets/js/vendor-all.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="vendor/parsley/dist/parsley.min.js"></script>

</body>
</html>


<script>

    $(document).ready(function(){
        $('#login_form').parsley();
        $('#login_form').on('submit', function(event){
            event.preventDefault();
            if($('#login_form').parsley().isValid())
            {
                $.ajax({
                    url:"action/login.php",
                    method:"POST",
                    data:$(this).serialize(),
                    dataType:'json',
                    beforeSend:function()
                    {
                        $('#login_button').attr('disabled', 'disabled');
                        $('#login_button').val('wait...');
                    },
                    success:function(data)
                    {
                        $('#login_button').attr('disabled', false);
                        if(data.error != '')
                        {
                            $('#error').html(data.error);
                            $('#login_button').val('Login');
                        }
                        else
                        {
                            window.location.href = data.url;
                        }
                    }
                })
            }
        });

    });

</script>