<?php 
      
      if(!empty($cookie_check)){$cookie=$cookie_check;}else{$cookie='';}
      if(empty($email))$email='';
      if(empty($password))$password='';
      
?>
<!--Login page  Wrap-->
<div class="data-wrap">
    <!--COl Wrapper-->  
    <div class="in-col-wrap clearfix">
        <!--Left Col-->
        <div class="in-left-col">
            <!--form inner col-->
            <figure class="index-logo">
                <img src="<?php echo BASE_URL . '/admin/' ?>images/logo.svg">
            </figure>
                <span style="color: red; margin-left: 159px;" class="error-msg"><?php if (!empty($message)) {
    echo $message;
} ?></span>
            <div class="index-form-wrap">
                <form onsubmit=" return validate()" method="Post" id="login_form">
                    <h1 class="index-comn-heading">Login  </h1>
                    <p class="index-note">Sign in to manage your Application </p>
                    <div id="emailerror" class="form-field-wrap">
                        <span class="ad-user"></span>
                        <input id="email" type="text" name="email" class="login_filed" data-validation="required email"  placeholder="Email ID" autocomplete="off" value="<?php echo $email; ?>">
                        <span class="bar"></span> 
                        <span class="error-mssg">Please Enter  Email</span>      
                    </div>
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <div id="passworderror" class="form-field-wrap">
                        <span class="ad-password"></span>
                        <input type="password" id="password" name="password" class="login_filed" data-validation="required"  placeholder="Password" value="<?php echo $password; ?>">
                        <span class="bar"></span>
                        <span class="error-mssg">Please Enter Password</span>
                    </div>
                    <div class="form-field-wrap">
                        <span class="rember-col">
                            <div class="th-checkbox">
                                <input type="hidden" name="remember_me" id="rem_value" value="<?php echo !empty($remember_me)?$remember_me:''; ?>">
                                <input class="filter-type filled-in" type="checkbox" name="rem_me" value="off" onclick="setRemember();" <?php echo !empty($cookie)?$cookie:''; ?> id="flowers" >
                                <label for="flowers" class="lbl-check"><span></span>Remember me</label>
                            </div>  
                        </span>  
                        <span class="forgot-pass"><a href="<?php echo SITE_URL.'/admin/forgotpassword' ?>">Forgot Password?</a></span>
                    </div>
                    <div class="form-field-wrap text-center">
                        <button  class="index-comn-btn">Login</button>
                    </div>
                </form>
            </div>
            <!--form inner col close-->
        </div>
        <!--Left Col-->
        <!--Right Col-->
        <!--  <div class="in-right-col">
           
         </div> -->
        <!--Right Col Close-->
    </div>
    <!--COl Wrapper-->
