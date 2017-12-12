<footer>
    <!--nav footer wrap -->
    <div class="footer-wrap">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <div class="inner-col-footer">
                        <h3 class="title"><a href="#">
                                <img src="<?php echo base_url() ?>assets/images/logo.svg">
                            </a>
                        </h3>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                            eiusmod tempor incididunt ut labore et dolore magna aliqua.
                            Ut enim ad minim veniam, quis nostrud exercitation ullamco
                            laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                            in
                        </p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="inner-col-footer">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="sports-col">
                                    <h3 class="title">Sports</h3>
                                    <ul>
                                        <li><a href="#">Football</a></li>
                                        <li><a href="#">Rugby</a></li>
                                        <li><a href="#">Tennis</a></li>
                                        <li><a href="#">Basketball</a></li>
                                        <li><a href="#">Football</a></li>
                                        <li><a href="#">Cricket</a></li>
                                        <li><a href="#">Golf</a></li>
                                        <li><a href="#">Boxing</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="sports-col">
                                    <h3 class="title">COMPANY</h3>
                                    <ul>
                                        <li><a href="#">About Us</a></li>
                                        <li><a href="#">Jackpot</a></li>
                                        <li><a href="#">Free Statistics</a></li>
                                        <li><a href="#">Free Tips</a></li>
                                        <li><a href="#">Contact Us</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="inner-col-footer">
                        <h3 class="title">Download the App</h3>
                        <p>
                            Lorem ipsum dolor sit amet,
                            consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                            et dolore magna aliqua.
                        </p>
                        <div class="download-app-bttn-wrap">
                            <ul>
                                <li><a href="#">
                                        <img src="<?php echo base_url() ?>assets/images/play-store.png" alt="Play Store">
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="<?php echo base_url() ?>assets/images/app-store.png" alt="App Store">
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--nav footer wrap close-->
    <!--Bottom Foot Wrapper-->
    <div class="bottom-footer clearfix">
        <div class="container clearfix">
            <div class="left-col">
                <p class="cpy-right-txt">&copy; 2017 CHEKIODDS. ALL RIGHTS RESERVED</p>
            </div>
            <div class="right-col">
                <div class="tnc-wrap">
                    <ul>
                        <li><a href="#">Terms and Conditions </a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="follow-wrap">
                    <ul>
                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i> </a></li>
                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
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
                <span class="cross" data-dismiss="modal"><img src="<?php echo base_url(); ?>assets/images/cross.svg"></span>
                <figure>
                    <img src="<?php echo base_url(); ?>assets/images/logo.svg" alt="checkiodds">
                </figure>
                <h2 class="log-sign-heading">Log in to Chekiodds</h2>
            </div>
            <span id="login_failure" style="color: #ff8787;position: absolute;left: 204px;font-size: large;top:86px;"></span>
            <div class="modal-body">
                <form action="" id="loginForm" method="Post">
                    <div class="form-field-wrap">
                        <input type="text" placeholder="Email" name="email" autocomplete="off">
                    </div>
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <div class="form-field-wrap">
                        <input type="password" placeholder="Password" name="password" autocomplete="off">
                    </div>
                    <input type="hidden" placeholder="Password" name="login_type" value="normal">
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
                        <span class="alrdy-signup">Don't have an account? <a href="javascript:void(0)" data-dismiss="modal" data-toggle="modal" data-target="#myModal-signup">SIGN UP</a></span>
                    </div>
                    <!--Signup with f g tw-->
                    <div class="login-fa-gp-tw">
                        <ul>
                            <li><a href="javascript:void(0)" onclick="fbLogin()" class="fb"><span class="facebook"><i class="fa fa-facebook" aria-hidden="true"></i></span>  <span class="txt">Sign Up with facebook</span></a></li>
                            <li><a href="javascript:void(0)" onclick="googleLogin()" class="gplus"><span class="googleplus"><i class="fa fa-google-plus" aria-hidden="true"></i></span>  <span class="txt">Sign Up with google</span></a></li>
                            <li><a href="javascript:void(0)" onclick="twitterLogin()" class="tw"><span class="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></span>  <span class="txt">Sign Up with twitter</span></a></li>
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
                <span style="background-image:url('<?php echo base_url() ?>public/web/images/back-btn.png')" class="back-btn"></span>
                <span class="cross" data-dismiss="modal"><img src="<?php echo base_url() ?>assets/images/cross.svg"></span>
                <figure>
                    <img src="<?php echo base_url() ?>assets/images/logo.svg" alt="checkiodds">
                </figure>
                <h2 class="log-sign-heading">SignUp to Chekiodds</h2>
                <h5 align="center" class="succssmsg" id="signup-success"></h5>
                <h5 align="center" class="errormsg"  id="signup-failmsg"></h5>
            </div>

            <div class="modal-body">
                <?php echo form_open_multipart('',array('id'=>'userSignUp','method'=>'post','name'=>'userSignUp','autocomplete'=>'Off')); ?>
                <div class="signup-form-wrapper">
                    <div class="step1">
                        <img class="loader-modal" style="display: none" id="procesing1" src="<?php echo base_url(); ?>/public/web/images/Processing.gif">
                        <div class="form-field-wrap text-center">
                            <figure id="targetLayer" class="show-upload-pic">
                            </figure>
                            <input type="file" onchange="showPreview(this)" name="userimage" id="signup-pic" style="display:none;">
                            <label for="signup-pic" class="upload-label"> Upload Picture</label>
                            <label class="errormsg" id="servererror"></label>
                        </div>

                        <div class="form-field-wrap">
                            <input type="text" autocomplete="off" id="name" name="name" placeholder="Full Name*">
                            <span class="errormsg" id="namemsg"></span>
                        </div>
                        <div class="form-field-wrap">
                            <input type="text" autocomplete="off" id="email" name="email" placeholder="Email*">
                            <span class="errormsg" id="emailmsg"></span>
                        </div>
                        <div class="form-field-wrap">
                            <div class="country-code">
                                <select id="country_code" name="country_code" class="selectpicker" data-live-search="true">

                                    <?php if(isset($country_code)):  foreach ($country_code as $key=>$values): ?>

                                        <option <?php  if($values->country_iso_code == '+91'){ echo 'selected';} ?> value="<?php echo $values->country_iso_code; ?>"><?php echo $values->country_iso_code; ?></option>
                                    <?php  endforeach; endif;?>

                                </select>
                            </div>
                            <input class="padding-input-left" autocomplete="off"  type="number" id="phone" name="phone" placeholder="Phone*">
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
                            <input id="confirmpass" autocomplete="off" name="confirmpass" type="password" placeholder="Confirm Password*">
                        </div>
                        <div class="form-field-wrap">
                           <span class="rember-col">
                              <div class="th-checkbox">
                                 <input class="filter-type filled-in" type="checkbox" name="privacy_policy" id="privacy_policy" value="I agree to terms and conditions">
                                 <label for="privacy_policy" class="lbl-check"><span></span>I agree to terms and conditions and privacy policy</label>

                              </div>
                           </span>
                            <span  class="errormsg" id="privacy_policydmsg"></span>
                        </div>
                        <div class="button-wrap text-center">
                            <button type="submit" id="submitt" class="commn-btn next-btn">Sign up</button>
                        </div>

                        <div class="form-field-wrap text-center">
                            <img  style="display: none" id="procesing" src="<?php echo base_url(); ?>/public/web/images/Processing.gif">

                        </div>

                    </div>

                </div>
                <div class="alrdy-act-wrap text-center">
                    <span class="alrdy-signup">Already have an account? <a href="javascript:void(0)" data-dismiss="modal" data-toggle="modal" data-target="#myModal-login">Login</a></span>
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
<script src="<?php echo base_url() ?>assets/js/commnjs/jquery.js"></script>
<script src="<?php echo base_url() ?>assets/js/plugin/bootstrap.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/plugin/bootstrap-select.js"></script>
<script src="<?php echo base_url() ?>assets/js/plugin/owl.carousel.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/plugin/app.js"></script>
<script src="<?php echo base_url() ?>assets/js/commnjs/custom.js"></script>


<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
<script src="<?php echo BASE_URL . '/web/' ?>js/commnjs/jquery-validation.js"></script>
<script src="https://apis.google.com/js/client:platform.js?onload=onLoadCallback" async defer></script>
<script src="<?php echo BASE_URL . '/web/' ?>js/commnjs/jquery.validate.js"></script>
<script src="<?php echo BASE_URL . '/web/' ?>js/commnjs/web.js"></script>

<!--js block close-->
</body>
</html>