<!DOCTYPE html>
<html>
<head>

</head>
<body>


<div class="container-fluid">
    <div class="row"><br>
        <div class="d-flex justify-content-center">

        <div class="col-sm-6">
            <div class="panel panel-primary">
               <div class="panel-heading">
            <h4 class="text-center m0"> Welcome to WP Anti-Copy</h4></div>
            <div class="panel-body">
            <div class="alert alert-warning">
  <strong>Hi!</strong> Welcome to WP Anti-Copy. You need to verify your purchase to continue. This is a one-time task and will not be repeated on this site.

All you need to verify is to put in the email you used to purchase WP Anti-Copy.
</div>
            <div class="account-wall">
                <img class="profile-img" src="<?php echo esc_url(plugins_url('asset/img/blue-icon-security.png',__FILE__)); ?>">
                <form class="form-signin" action="" method="post">
                <input type="text" name="<?php echo esc_attr($gdprlicenseacceptemailfield); ?>" class="form-control" placeholder="Your Email" required autofocus>
				<br>
				<input type="text" name="<?php echo esc_attr($gdprlicenseacceptordercodefield); ?>" class="form-control" placeholder="Your Order Code" required autofocus>
				<p><center><?php echo $thhisactivationerr; ?></center></p>
                <button class="btn btn-lg btn-primary btn-block" type="submit" name="<?php echo esc_attr($gdprlicenseacceptbtn); ?>">
                    Submit</button><!-- 
                    <a href="http://wpgdprfix.in" style="text-align: center;" class="need-help">Didn't buy WP GDPR Fix yet?Buy it here
                    </a><br/>
				
                <a href="https://teknikforce.com/support/" style="text-align: center;" class="need-help">Need help? </a><span class="clearfix"></span> -->
                <a href="http://teknikforce.com" class="text-center new-account" target="_BLANK">Didn't buy WP Anti-Copy yet? Buy it here</a>
                <a href="https://teknikforce.com/support/" class="text-center new-account" target="_BLANK">Need help?</a>
                </form>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>
</div>
<style type="text/css">

    .form-signin
{
    max-width: 330px;
    padding: 15px;
    margin: 0 auto;
}
.form-signin .form-signin-heading, .form-signin .checkbox
{
    margin-bottom: 10px;
}
.form-signin .checkbox
{
    font-weight: normal;
}
.form-signin .form-control
{
    position: relative;
    font-size: 16px;
    height: auto;
    padding: 10px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.form-signin .form-control:focus
{
    z-index: 2;
}
.form-signin input[type="text"]
{
    margin-bottom: -1px;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
}
.form-signin input[type="password"]
{
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
.account-wall
{
    margin-top: 20px;
    padding: 40px 0px 20px 0px;
    background-color: #f7f7f7;
    -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    margin: 8px;
}
.login-title
{
    color: #555;
    font-size: 18px;
    font-weight: 400;
    display: block;
}
.profile-img
{
    width: 96px;
    height: 96px;
    margin: 0 auto 10px;
    display: block;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
}
.need-help
{
    margin-top: 10px;
}
.new-account
{
    display: block;
    margin-top: 10px;
}
.panel-primary {
    border-color: #525863;
}
.panel-primary>.panel-heading {
    color: #fff;
     background: linear-gradient(#525863, #444a55);
    border-color: #525863;
}
.m0{
    margin:0px;
}
.panel {    
    border: 1px solid;   
}
.panel-heading {
    padding: 10px 15px;
    border-bottom: 1px solid transparent;
    border-top-left-radius: 0px;
    border-top-right-radius: 0px;
}
.alert-warning {
  
    margin: 8px;
}
</style>
</body>


</html>