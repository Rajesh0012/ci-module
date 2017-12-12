<!--Bottom Foot Wrapper-->
<style>

    .errormsg{

        color:red;
    }
</style>
            <div class="bottom-footer clearfix">
               <div class="container clearfix">
                  <div class="left-col">
                     <p class="cpy-right-txt">&copy; 2017 CHEKIODDS. ALL RIGHTS RESERVED</p>
                  </div>
                  <div class="right-col">
                     <div class="tnc-wrap">
                        <ul>
                           <li><a href="javascript:void(0)">Terms and Conditions </a></li>
                           <li><a href="javascript:void(0)">Privacy Policy</a></li>
                        </ul>
                     </div>
                     <div class="follow-wrap">
                        <ul>
                           <li><a href="javascript:void(0)"><i class="fa fa-facebook" aria-hidden="true"></i> </a></li>
                           <li><a href="javascript:void(0)"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                           <li><a href="javascript:void(0)"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
            <!--Bottom Foot Wrapper Close-->
         </footer>
         <!--footer-->  
      </div>
      <!--data wrap-->
<!--Login Modal-->
      <!-- Modal -->
      <div id="myModal-login" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content log-sign-modal">
               <div class="log-sign-modal-header">
                  <span class="cross" data-dismiss="modal"><img src="<?php echo BASE_URL . '/web/' ?>images/cross.svg"></span>
                  <figure>
                     <img src="<?php echo BASE_URL . '/web/' ?>images/logo.svg" alt="checkiodds">
                  </figure>
                  <h2 class="log-sign-heading">Log in to Chekiodds</h2>
               </div>
               <div class="modal-body">
                   <form action="<?php echo SITE_URL . '/web' ?>/auth/login" id="loginForm" method="Post">
                  <div class="form-field-wrap commn-animate-error">
                     <input type="text" placeholder="Email" name="email">
                  </div>
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">     
                  <div class="form-field-wrap commn-animate-error">
                     <input type="password" placeholder="Password" name="password">
                  </div>
                  <div class="form-field-wrap clearfix">
                     <div class="left-col"></div>
                     <div class="right-col">
                        <span class="fogot"><a href="javascript:void(0)">Forgot Password?</a></span>  
                     </div>
                  </div>
                  <div class="button-wrap text-center">
                     <button class="commn-btn">Login</button>
                  </div>
                  <div class="alrdy-act-wrap text-center">
                     <span class="alrdy-signup">Don't have an account? <a href="javascript:void(0)">SIGN UP</a></span>
                  </div>
                  <!--Signup with f g tw-->
                  <div class="login-fa-gp-tw">
                     <ul>
                        <li><a href="javascript:void(0)" class="fb"><span class="facebook"><i class="fa fa-facebook" aria-hidden="true"></i></span>  <span class="txt">Sign Up with facebook</span></a></li>
                        <li><a href="javascript:void(0)" class="gplus"><span class="googleplus"><i class="fa fa-google-plus" aria-hidden="true"></i></span>  <span class="txt">Sign Up with google</span></a></li>
                        <li><a href="javascript:void(0)" class="tw"><span class="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></span>  <span class="txt">Sign Up with twitter</span></a></li>
                     </ul>
                  </div>
                  <!--Signup with f g tw-->
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!--Login Modal close-->


      <!--Signup Modal-->
      <!-- Modal -->


      <div id="myModal-signup" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content log-sign-modal">
               <div class="log-sign-modal-header">
                  <span style="background-image:url('images/back-btn.png')" class="back-btn"></span>   
                  <span class="cross" data-dismiss="modal"><img src="<?php echo BASE_URL . '/web/' ?>images/cross.svg"></span>
                  <figure>
                     <img src="<?php echo BASE_URL . '/web/' ?>images/logo.svg" alt="checkiodds">
                  </figure>
                  <h2 class="log-sign-heading">SignUp to Chekiodds</h2>
               </div>

               <div class="modal-body">
                   <?php echo form_open_multipart('',array('id'=>'userSignUp','method'=>'post','name'=>'userSignUp')); ?>
                  <div class="signup-form-wrapper">
                     <div class="step1">
                        <div class="form-field-wrap text-center">
                            <div id="targetLayer"></div>
                           <figure class="show-upload-pic" style="background-image:url('https://hdwallsource.com/img/2014/6/game-wallpapers-hd-19848-20345-hd-wallpapers.jpg')">
                           </figure>
                           <input type="file" onchange="showPreview(this)" name="userimage" id="signup-pic" style="display:none;">
                           <label for="signup-pic" class="upload-label"> Upload Pictures</label>
                        </div>
                        <!--<div class="form-field-wrap">
                           <input type="text" name="name" placeholder="Name*">
                        </div>-->
                         <div class="form-field-wrap">
                           <input required type="text" name="email" placeholder="Email*">
                             <span class="errormsg" id="emailmsg"></span>
                        </div>
                        <div class="form-field-wrap">
                           <div class="country-code">
                              <select required name="country_code" class="selectpicker" data-live-search="true">
                                 <?php  foreach ($country_code as $key=>$values): ?>

                                 <option><?php echo $values->country_iso_code; ?></option>


                               <?php  endforeach;?>

                              </select>
                           </div>
                           <input type="text" id="phone" name="phone" placeholder="Phone*">
                            <span class="errormsg" id="phonelmsg"></span>
                        </div>
                        <div class="button-wrap text-center">
                           <button type="submit" id="nextt" class="commn-btn next-btn next-btn1">Next ></button>
                        </div>
                     </div>

                     <div class="step2">
                        <div class="form-field-wrap">
                           <input type="password" id="password" name="password" placeholder="Password*">
                            <span class="errormsg" id="passwordmsg"></span>
                        </div>
                        <div class="form-field-wrap">
                           <input id="confirmpass" name="confirmpass" type="password" placeholder="Confirm Password*">
                        </div>
                        <div class="form-field-wrap">
                           <span class="rember-col">
                              <div class="th-checkbox">
                                 <input class="filter-type filled-in" type="checkbox" name="privacy_policy" id="privacy_policy" value="I agree to terms and conditions">
                                 <label for="privacy_policy" class="lbl-check"><span></span>I agree to terms and conditions and privacy policy</label>
                              <span class="errormsg" id="privacy_policydmsg"></span>
                              </div>
                           </span>
                        </div>
                        <div class="button-wrap text-center">
                           <button type="submit" id="submitt" class="commn-btn next-btn">Sign up</button>
                        </div>
                     </div>

                  </div>
                  <div class="alrdy-act-wrap text-center">
                     <span class="alrdy-signup">Already have an account? <a href="javascript:void(0)">Login</a></span>
                  </div>
                   <?php echo form_close(); ?>
               </div>


               <div class="dots-circle-wrap">
                  <ul>
                     <li><span class="active dt1"></span></li>
                     <li><span class="dt2"></span></li>
                  </ul>
               </div>
            </div>
         </div>
      </div>




      <!--Signup Modal-->

      <!--js block-->
      <script src="<?php echo BASE_URL . '/web/' ?>js/commnjs/jquery.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
      <script src="<?php echo BASE_URL . '/web/' ?>js/commnjs/jquery-validation.js"></script>
      <script src="<?php echo BASE_URL . '/web/' ?>js/plugin/bootstrap.min.js"></script>
      <script src="<?php echo BASE_URL . '/web/' ?>js/plugin/bootstrap-select.js"></script>
      <script src="<?php echo BASE_URL . '/web/' ?>js/plugin/owl.carousel.min.js"></script>
      <script src="<?php echo BASE_URL . '/web/' ?>js/plugin/app.js"></script>
      <script src="<?php echo BASE_URL . '/web/' ?>js/commnjs/custom.js"></script>
      <script src="<?php echo BASE_URL . '/web/' ?>js/commnjs/jquery.validate.js"></script>
      <script src="<?php echo BASE_URL . '/web/' ?>js/commnjs/web.js"></script>

      <!--js block close-->
   </body>
</html>