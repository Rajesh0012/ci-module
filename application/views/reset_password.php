 <!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, 
         minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <title>chekiodds</title>
      <link rel="icon" type="image/png" sizes="32x32" href="<?php echo BASE_URL . '/admin/' ?>images/facv-icon.png">
      <!-- Bootstrap Core CSS -->
      <link rel="stylesheet" href="<?php echo BASE_URL . '/admin/' ?>css/bootstrap.css">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css" rel="stylesheet">
      <link href="<?php echo BASE_URL . '/admin/' ?>css/style.css" rel="stylesheet">
      <link href="<?php echo BASE_URL . '/admin/' ?>css/media.css" rel="stylesheet">
   </head>
   <body>
      <!--Login page  Wrap-->
      <div class="data-wrap">
         <!--COl Wrapper-->  
         <div class="in-col-wrap clearfix">
            <!--Left Col-->
            <div class="in-left-col">
                <form action="" method="Post">
               <!--form inner col-->
                  <figure class="index-logo">
                  <img src="<?php echo BASE_URL . '/admin/' ?>images/logo.svg">
               </figure>
               <div class="index-form-wrap">
                   <?php 
                   if(!empty($message_error)){
                   ?>
                   
                       <span style="color: red;position: absolute;top: -17px;left: 112px;"><?php if(!empty($message_error)){ echo $message_error;}?></span>
                   <?php
                       }
                       if(!empty($message_success)){
                   ?>
                       <span style="color: green;position: absolute;top: -17px;left: 32px;"><?php if(!empty($message_success)){ echo $message_success;}?></span>
                    <?php
                           
                       }?>
                   
                  <h1 class="index-comn-heading">Reset Password</h1>
                  <p class="index-note"></p>
                  <div class="form-field-wrap">
                     <span class="ad-password"></span>
                     <input type="password" class="login_filed" maxlength="40" placeholder="Enter New Password" name="new_pass"> <span class="error-mssg"></span>
                     <span class="bar"></span>
                  </div>
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <div class="form-field-wrap">
                      <span class="ad-password"></span>
                     <input type="password" class="login_filed" maxlength="40" placeholder="Enter Confirm Password" name="con_pass"> <span class="error-mssg"></span>
                     <span class="bar"></span>
                  </div>
                 
                 
                  <div class="form-field-wrap text-center">
                     <button class="index-comn-btn">Submit </button>
                  </div>

                  
               </div>
               </form>
               <!--form inner col close-->
            </div>
            <!--Left Col-->
         </div>
         <!--COl Wrapper-->
         <!--Footer-->
         <footer>
            <div class="left-col">
               <ul>
                  <li><a href="javascript:void(0)">About Us </a></li>
                  <li><a href="javascript:void(0)">Term and Condition</a></li>
                  <li><a href="javascript:void(0)">Privacy and Policy</a></li>
               </ul>
            </div>
         </footer>
         <!--Footer close-->
      </div>
      <!--Login page  Wrap close-->
      <!--Jquery-->
      <script src="<?php echo BASE_URL . '/admin/' ?>js/jquery.js"></script>
      <script src="<?php echo BASE_URL . '/admin/' ?>js/bootstrap.min.js"></script>
      <script src="<?php echo BASE_URL . '/admin/' ?>js/custom.js"></script>
      <!--Jquery Close-->
   </body>
</html>