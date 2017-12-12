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
               <span class="forgot-error"><?php if (!empty($message)) {  echo $message; } ?></span>
               <form action="" method="Post">
               <div class="index-form-wrap">
                  <h1 class="index-comn-heading">Forgot Password  </h1>
                  <p class="index-note">Enter your email address. You  will receive an email with a link to reset your password</p>
                   <div class="form-field-wrap">
                     <span class="ad-user"></span>
                     <input type="text" class="login_filed" data-validation="required" placeholder="Email ID" name="email">
                     <span class="bar"></span> 
                     <span class="error-mssg">Please Enter  Email</span>      
                   </div>
                   <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                 
                  <div class="form-field-wrap text-center">
                      <button class="index-comn-btn" type="submit">Reset Password </button>
                  </div>
                  <div class="form-field-wrap text-center">
                    <span class="bck-sign"><a href="<?php echo SITE_URL.'/admin' ?>">Back To Sign In</a></span>
                  </div>
               </div>
               </form>
               <!--form inner col close-->
            </div>
            <!--Left Col-->
         </div>
         <!--COl Wrapper-->