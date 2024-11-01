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
            <h4 class="text-center m0"> Welcome to WP Anti-Copy </h4></div>
            <div class="panel-body">
            <div class="alert alert-warning">
  <strong>Hi!</strong> Welcome to WP Anti-Copy to activate this product please fill in a valid email id. You'll receive an activation key that you can fill in the box below in your email.
</div>
            <div class="account-wall">
                <img class="profile-img" src="<?php echo esc_url(plugins_url('asset/img/blue-icon-security.png',__FILE__)); ?>">
                <form class="form-signin" action="" method="post">
				<?php
				if(!$askfor_code){
				?>
                <input type="text" name="<?php echo esc_attr($lisence_input_name); ?>" class="form-control" placeholder="Your Name" required autofocus>
				<br>
				<input type="email" name="<?php echo esc_attr($lisence_input_email); ?>" class="form-control" placeholder="Your Email" required autofocus>
				<?php }else{ ?>
				<label>Please Enter Verification Code We Have Sent</label>
				<input type="hidden" name="<?php echo esc_attr($lisence_input_name); ?>" value="<?php echo esc_attr($_POST[$lisence_input_name]); ?>" class="form-control" placeholder="Your Name" required autofocus>
				<input type="hidden" name="<?php echo esc_attr($lisence_input_email); ?>" value="<?php echo esc_attr($_POST[$lisence_input_email]); ?>" class="form-control" placeholder="Your Email" required autofocus>
				<input type="text" name="<?php echo esc_attr($lisence_order_code); ?>" class="form-control" placeholder="Enter Verification Code" required autofocus>
				<?php } ?>
				<p><center><?php echo $thhisactivationerr; ?></center></p>
				<input type="hidden" name="<?php echo esc_attr($gdprlicenseacceptbtn); ?>_pcsrf" value="<?php echo wp_create_nonce($gdprlicenseacceptbtn.'_pcsrf'); ?>">
                <button class="btn btn-lg btn-primary btn-block" type="submit" name="<?php echo esc_attr($gdprlicenseacceptbtn); ?>">
                    Submit</button>
				<a href="http://teknikforce.com" class="text-center new-account" target="_BLANK">
                If you don't receieve the activation key, please contact support at http://teknikforce.com</a>
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