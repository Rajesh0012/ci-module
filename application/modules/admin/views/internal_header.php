<?php 
$method=$this->router->fetch_method();
//echo $method; die;
?>
<style>
    .error{
        color: red;
    }
</style>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, 
         minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <title>Chekiodds</title>
      <link rel="icon" type="image/png" sizes="32x32" href="<?php echo BASE_URL . '/admin/' ?>images/facv-icon.png">
      <!-- Bootstrap Core CSS -->
      
      <link href="<?php echo BASE_URL . '/admin/' ?>css/bootstrap.css" rel="stylesheet">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">
      <link rel="stylesheet" href="<?php echo BASE_URL . '/admin/' ?>css/bootstrap-datetimepicker.min.css" />
      <link rel="stylesheet" href="<?php echo BASE_URL . '/admin/' ?>css/bootstrap-select.min.css">
      <link href="<?php echo BASE_URL . '/admin/' ?>css/style.css" rel="stylesheet">
      <link href="<?php echo BASE_URL . '/admin/' ?>css/media.css" rel="stylesheet">

       <script src="https://www.w3schools.com/lib/w3.js"></script>
      <script src="https://cdn.tinymce.com/4/tinymce.min.js"></script>

   </head>
   <script>
       var siteurl='<?php echo SITE_URL; ?>';
   </script>
   <body>
      <!--data Wrap-->
      <div class="in-data-wrap">
         <!--aside-->
         <aside>
            <!--left panel-->
            <div class="left-panel">
               <div class="inner-left-pannel">
                  <div class="user-short-detail">
                     <figure>   
                        <img src="<?php echo BASE_URL . '/admin/' ?>images/user-placeholder.png" id="lefft-logo">
                     </figure>
                     <span class="user-name">John Doe</span> 
                  </div>
                  <div class="left-menu mCustomScrollbar _mCS_1 mCS-autoHide">
                     <ul>
                        <li>
                           <a href="javascript:void(0);">
                           <span class="dashboard"></span><label class="nav-txt">Dashboard</label>
                           </a>
                        </li>
                        <li>
                             <a class="<?php if(!empty($method) && $method === 'homePage'){ echo "active";} ?>" href="<?php echo SITE_URL; ?>/admin/Home/homePage/" class="">
                                 <span class="jackpot"></span><label class="nav-txt">Home Management</label>
                             </a>
                         </li>
                        <li>
                           <a class="<?php if(!empty($method) && $method === 'user_list' || $method == 'add_user'){ echo "active";} ?>" href="<?php echo SITE_URL; ?>/admin/users/user_list" class="">
                           <span class="usermangment"></span><label class="nav-txt">User Management</label>
                           </a>
                        </li>
                        <li>
                           <a href="<?php echo SITE_URL.'/admin/freetips' ?>" class="<?php if(!empty($method) && $method=='index' || $method == 'add_game_match' || $method == 'add_feature_match'){ echo "active";} ?>">
                           <span class="free-tip"></span><label class="nav-txt">Free Tips</label>
                           </a>
                        </li>
                        <li>
                           <a href="javascript:void(0);" class="">
                           <span class="sub-admin"></span><label class="nav-txt">Sub Admins</label>
                           </a>
                        </li>
                        <li>
                           <a class="<?php if(!empty($method) && $method === 'get_sports_list'){ echo "active";} ?>" href="<?php echo base_url(); ?>admin/sports/get_sports_list">
                           <span class="sports"></span><label class="nav-txt">Sports</label>
                           </a>
                        </li>
                        <li>
                           <a  href="javascript:void(0);" class="">
                           <span class="add"></span><label class="nav-txt">Advertisement</label>
                           </a>
                        </li>
                         <li>
                             <a  href="<?php echo base_url()?>admin/banner/banner_list" class="<?php if(!empty($method) && $method === 'banner_list' || $method == 'editBanner' || $method == 'add_banner'){ echo "active";} ?>">
                                 <span class="add"></span><label class="nav-txt">banner</label>
                             </a>
                         </li>
                        <li>
                           <a href="javascript:void(0);" class="">
                           <span class="notification-bell"></span><label class="nav-txt">Push Notification</label>
                           </a>
                        </li>
                        <li>
                           <a href="javascript:void(0);" class="">
                           <span class="app-content"></span><label class="nav-txt">App Content</label>
                           </a>
                        </li>
                        <li>
                           <a href="javascript:void(0);" class="">
                           <span class="jackpot"></span><label class="nav-txt">Jackpot</label>
                           </a>
                        </li>
                         
                     </ul>
                  </div>
               </div>
            </div>
            <!--left panel-->
         </aside>
         <div class="right-panel">
            <!--Header-->
            <header>
               <!--toggle button-->
               <div class="toggle-btn-wrap">
                  <span class="line-bar"></span>
                  <span class="line-bar shot-line-br"></span>
                  <span class="line-bar"></span>
               </div>
               <!--toggle button close-->  
               <!--nav brand wrap-->
               <div class="nav-brand">
                  <a href="javascript:void(0)" class="brand" >
                  <img src="<?php echo BASE_URL . '/admin/' ?>images/logo.svg" title="Admin Logo">  
                  </a>
               </div>
               <!--nav brand wrap close-->
               <!--User Setting-->   
               <div class="user-setting-wrap">
                  <div class="user-pic-wrap">
                     <ul>
                        <!--<li> 
                           <img src="<?php //echo BASE_URL . '/admin/' ?>images/user.png">   
                        </li>!-->
                        <li> 
                           <a href="javascript:void(0);"><img src="<?php echo BASE_URL . '/admin/' ?>images/notification.svg" title="Notification"></a>
                           <span class="noti-digit">0</span>
                        </li>
                        <li> 
                           <a href="javascript:void(0);"><img src="<?php echo BASE_URL . '/admin/' ?>images/setting.svg" title="Setting"></a>
                        </li>
                        <li> 
                           <a href="javascript:void(0);"  data-toggle="modal" data-target="#myModal-trash"><img src="<?php echo BASE_URL . '/admin/' ?>images/logout.svg" title="Logout"></a>
                        </li>
                     </ul>
                  </div>
               </div>
               <!--User Setting wrap close-->   
            </header>
            <!--Header close-->
            
            <div id="myModal-trash" class="modal fade" role="dialog">
                <div class="modal-dialog modal-sm">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header modal-alt-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title modal-heading">Logout </h4>
                        </div>
                        <div class="modal-body">
                            <p class="modal-para">Are you sure want to logout?</p>
                            <div class="button-wrap">
                                <input data-dismiss="modal" type="button" value="Cancel" class="commn-btn cancel" name="">
                                <input type="submit" onclick="Logout();" value="Logout" class="commn-btn save">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>