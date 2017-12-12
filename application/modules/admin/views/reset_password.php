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
               <span class="error-msg"><?php if (!empty($message)) {  echo $message; } ?></span>
               <div class="index-form-wrap">
               <form action="" method="Post">
                  <h1 class="index-comn-heading">Reset Password  </h1>
                  <p class="index-note"></p>
                  
                  <div class="form-field-wrap">
                     <span class="ad-password"></span>
                     <input type="password" class="login_filed" data-validation="required" placeholder="Enter New Password" name="new_password"> <span class="error-mssg"></span>
                     <span class="bar"></span>
                  </div>
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <div class="form-field-wrap">
                      <span class="ad-password"></span>
                     <input type="password" class="login_filed" data-validation="required" placeholder="Enter Confirm Password" name="con_password"> <span class="error-mssg"></span>
                     <span class="bar"></span>
                  </div>
                 
                 
                  <div class="form-field-wrap text-center">
                      <button class="index-comn-btn" type="submit">Submit </button>
                  </div>

                  <div class="form-field-wrap text-center">
                        <span class="bck-sign"><a href="<?php echo SITE_URL.'/admin' ?>">Back to SignIn</a></span>
                  </div>
                  
                  </form>
               </div>
               
               <!--form inner col close-->
            </div>
            <!--Left Col-->
         </div>
         <!--COl Wrapper-->
        