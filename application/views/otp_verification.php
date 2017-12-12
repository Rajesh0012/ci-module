<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <title>chekiodds</title>
      <!-- Bootstrap Core CSS -->
      <link href="<?php echo SITE_URL . '/web-html/' ?>css/plugin/bootstrap.css" rel="stylesheet">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css" rel="stylesheet">
      <link rel="stylesheet" href="<?php echo SITE_URL . '/web-html/' ?>css/plugin/owl.carousel.min.css">
      <link href="<?php echo SITE_URL . '/web-html/' ?>css/style.css" rel="stylesheet">
      <link href="<?php echo SITE_URL . '/web-html/' ?>css/media.css" rel="stylesheet">
      <!-- <link href="css/media.css" rel="stylesheet"> -->
   </head>
   <body>
      <!--data wrap-->
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
      <div class="data-wrap">
         <div class="otp-wrapper log-sign-modal">
            <div class="">
                    <div class="brand-wrap">
                            <a href="javascript:void()"><img src="<?php echo BASE_URL . '/web/' ?>images/logo.svg" alt="chekiodds"></a>
                    </div>


               <!--<div class="row">
                  <h2 class="modal-heading-otp">
                     Enter OTP
                     <span class="skip"></span>
                  </h2>
               </div>!-->
               <div class="row">
                  <p class="commn-para text-center">
                     <?php echo !empty($msg)?$msg:''; ?>                                                                                                            
                  </p>
               </div>
               <!--<form method="post" id="validate_forgot_otp_form" action="/Otp_verify">
                  <div class="row m-b-lg">
                     <div class="form-filed-wrap text-center">
                        <input type="text" name="otp1" class="forgot-otp-box otp-box sms-no-box" maxlength="1">
                        <input type="text" name="otp2" class="forgot-otp-box otp-box sms-no-box" maxlength="1">
                        <input type="text" name="otp3" class="forgot-otp-box otp-box sms-no-box" maxlength="1">
                        <input type="text" name="otp4" class="forgot-otp-box otp-box sms-no-box" maxlength="1">
                      </div>
                  </div>
                   <input type="hidden" name="<?php //echo $this->security->get_csrf_token_name(); ?>" value="<?php //echo $this->security->get_csrf_hash(); ?>">
                   <input type="hidden" name="checkotp" value="<?php //echo !empty($_GET['check'])?$_GET['check']:''; ?>">
                  
                  <!--<span class="succ-otp-mssg"><span class="otp-check"></span>Send Otp</span!-->
                  <!--<div class="row m-b-lg">
                     <div class="form-filed-wrap">
                        <div class="col-lg-2 col-md-2 col-sm-2"></div>
                        <div class="col-lg-8 col-md-8 col-sm-8">
                           <button id="forgot_validate_otp_button" class="modal-index-bttn">Submit</button>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2"></div>
                     </div>
                  </div>
               </form>!-->
            </div>
            <!--<span class="time">Time:- </span>
            <div id="timer" class="timeing">1:54</div>
            <div class="resend-wrap">
               Resend OTP
            </div>!-->
         </div>
      </div>
      <!--data wrap-->
      <!--js block-->
      <script src="<?php echo SITE_URL . '/web-html/' ?>js/commnjs/jquery.js"></script>
      <script src="<?php echo SITE_URL . '/web-html/' ?>js/commnjs/bootstrap.min.js"></script>
      <script src="<?php echo SITE_URL . '/web-html/' ?>js/commnjs/owl.carousel.min.js"></script>
      <script src="<?php echo SITE_URL . '/web-html/' ?>js/commnjs/app.js"></script>
      <!--js block close--> 

      <script type="text/javascript">
         var timeoutHandle;
         function countdown(minutes) {
             var seconds = 60;
             var mins = minutes
             function tick() {
                 var counter = document.getElementById("timer");
                 var current_minutes = mins-1
                 seconds--;
                 counter.innerHTML =
                 current_minutes.toString() + ":" + (seconds < 10 ? "0" : "") + String(seconds);
                 if( seconds > 0 ) {
                     timeoutHandle=setTimeout(tick, 1000);
                 } else {
         
                     if(mins > 1){
         
                        // countdown(mins-1);   never reach “00″ issue solved:Contributed by Victor Streithorst
                        setTimeout(function () { countdown(mins - 1); }, 1000);
         
                     }
                 }
             }
             tick();
         }
         
         countdown(2);
         

         $('.sms-no-box').keyup(function(){
		if(this.value.length == 1){
			$(this).next('.sms-no-box').focus();
		}
         });

    
      </script>
      <script type="text/javascript">
    $(function () {
        $(document).keydown(function (e) {
            return (e.which || e.keyCode) != 116;
        });
    });
</script>

   </body>
</html>