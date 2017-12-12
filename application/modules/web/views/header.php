<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>chekiodds</title>
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url() ?>assets/images/facv-icon.png">
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url() ?>assets/css/plugin/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/plugin/bootstrap-select.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/plugin/owl.carousel.min.css">
    <link href="<?php echo base_url() ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/css/media.css" rel="stylesheet">
    <script>
        var siteurl='<?php echo SITE_URL; ?>';
    </script>
    <script>
        var csrf_value='<?php echo $this->security->get_csrf_hash(); ?>';
    </script>
</head>
<body>
<div style="display: none" class="loader" id="loader"></div>
<!--data wrap-->
<div class="data-wrap">
    <!--header Section-->
    <header>
        <!--Top nav-->
        <div class="container">
            <div class="top-nav clearfix">
                <!--bet brand wrap-->
                <div class="top-brand-wrap">
                    <a href="javascript:void(0)"><img src="<?php echo BASE_URL . '/web/' ?>images/logo.svg" alt="chekiodds"></a>
                </div>
                <!--bet brand wrap close-->
                <!--Top nav menu-->
                <div class="top-menu-wrap">
                    <span class="cross close-nave-top"><img src="<?php echo BASE_URL . '/web/' ?>images/cross.svg"></span>
                    <ul>
                        <li>
                            <a href="javascript:void(0)">Live Score</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">Home</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">Jackpot  </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">Free Statistics  </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">Free Tips </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <span class="select-odds-cart"></span>
                                <select class="form-control select-odds">
                                    <option>Odds Format  </option>
                                    <option>Fractional </option>
                                    <option>Decimal  </option>
                                </select>
                            </a>
                        </li>
                    </ul>
                </div>
                <!--Top nav menu close-->
                <!--Toggle-btn-wrap-->
                <div class="toggle-wrap  toggle-top-nave">
                    <span class="circle"></span>
                    <span class="circle"></span>
                    <span class="circle"></span>
                </div>
                <!--Toggle-btn-wrap-->
                <!--login signup  search wrap-->
                <div class="log-signup-srch-wrap">
                    <div class="lss-wrap">
                        <ul>
                            <li><a href="javascript:void(0)"><span class="search-header"></span></a></li>
                            <?php if(!empty($_SESSION['user_id'])){
                                ?>
                                <li><a href="javascript:void(0)" class="user-clk">
                                        <figure class="user-pic-top" style="background-image:url(<?php  if(isset($_SESSION['image']) && !empty($_SESSION['image'])){ echo $_SESSION['image'];}else{ echo BASE_URL.'/web/images/user_logo.png';} ?>)"> </figure>
                                        <span class="user_name"><?php echo !empty($_SESSION['full_name'])?$_SESSION['full_name']:''; ?></span>
                                    </a>
                                </li>
                                <?php
                            }else{
                                ?>
                                <li><a href="javascript:void(0)" data-toggle="modal" data-target="#myModal-login">Login</a></li>
                                <li><a href="javascript:void(0)" data-toggle="modal" data-target="#myModal-signup">Signup</a></li>
                                <?php
                            }?>

                        </ul>
                    </div>
                    <ul class="setting-top-wrap">
                        <li><a href="javascript:void(0)"><span class="seting-icon"></span> Setting</a></li>
                        <li><a href="<?php echo SITE_URL.'/web/logout'; ?>"><span class="logout-icon"></span> Logout</a></li>
                    </ul>

                </div>
                <!--login signup  search wrap-->
            </div>
        </div>
        <!--Top nav close-->
        <!--Nav Sports Nav-->
        <div class="nav-sports-nav clearfix">
            <div class="container">
                <div class="sport-nav-menu">
                    <div id="mobile-owl" class="owl-carousel">

                        <div class="item">
                            <a href="<?php echo base_url() ?>web/" class="<?php if(empty($_GET['id'])){ echo "active";} ?> ">
                                <span class="sport-icon all-sports"></span>
                                <span class="sports-name">All Sports</span>
                            </a>
                        </div>

                        <?php if(isset($sports) && !empty($sports)): foreach ($sports as $key=>$values): ?>
                            <div class="item">

                                <a class="<?php if($this->input->get('id') == $values->sport_id) { echo 'active';} ?>" href="<?php echo base_url() ?>web/?id=<?php echo $values->sport_id ?>">
                                    <span class="sport-icon <?php echo strtolower($values->sport_name); ?>"></span>
                                    <span class="sports-name"><?php echo ucfirst($values->sport_name); ?></span>
                                </a>
                            </div>
                        <?php endforeach;
                        else:?>
                            <div class="item">
                                <a href="<?php echo base_url() ?>web">

                                    <span class="sports-name">No Result Found</span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="betslip-btn-wrap">
                    <a href="javascript:void(0)">Betslip <span class="digit">0</span></a>
                </div>
            </div>
        </div>
        <!--Nav Sports Nav close-->
    </header>
    <!--header Section close-->
    <!--Search List wrap-->
    <div class="search-result-wrap">
        <div class="search-box-btn">
            <ul>
                <li><input type="text" placeholder="Search..."> </li>
                <li><button class="commn-btn search-btn">Search</button></li>
            </ul>
        </div>
        <div class="search-list-wrap">
            <ul>
                <li><a href="#" class="clearfix">
                        <span class="sport-icon" style="background-image: url(<?php echo base_url() ?>assets/images/football-b.svg);"></span>
                        <span class="team-sports-wrap">
                     <span class="title">Man United</span>
                     <span class="team">Trophy Multiples | All Football Specials</span>
                     </span>
                    </a>
                </li>
                <li><a href="#" class="clearfix">
                        <span class="sport-icon" style="background-image: url(<?php echo base_url() ?>assets/images/football-b.svg);"></span>
                        <span class="team-sports-wrap">
                     <span class="title">Man United</span>
                     <span class="team">Trophy Multiples | All Football Specials</span>
                     </span>
                    </a>
                </li>
                <li><a href="#" class="clearfix">
                        <span class="sport-icon" style="background-image: url(<?php echo base_url() ?>assets/images/football-b.svg);"></span>
                        <span class="team-sports-wrap">
                     <span class="title">Man United</span>
                     <span class="team">Trophy Multiples | All Football Specials</span>
                     </span>
                    </a>
                </li>
                <li><a href="#" class="clearfix">
                        <span class="sport-icon" style="background-image: url(<?php echo base_url() ?>assets/images/football-b.svg);"></span>
                        <span class="team-sports-wrap">
                     <span class="title">Man United</span>
                     <span class="team">Trophy Multiples | All Football Specials</span>
                     </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!--Search List wrap Close-->
    <!--Add block section-->
    <div class="add-block-wrap">
        <div class="container">
            <div class="block-add clearfix">
                <div class="top-left-banner-wrap">
                    <img src="<?php echo base_url() ?>assets/images/addblock-img-1.png" class="banner-add">
                </div>
                <div class="top-right-banner-wrap">
                    <img src="<?php echo base_url() ?>assets/images/addblock-img-2.png" class="banner-add">
                </div>
            </div>
        </div>
    </div>
    <!--Add block section close-->
    <!--Slider Banner Wrap-->
    <div class="slider-banner-wrap">
        <div class="container">
            <div id="homebanner-owl" class="owl-carousel">
                <div class="item">
                    <img src="<?php echo base_url() ?>assets/images/basket-banner.png">
                </div>
                <div class="item">
                    <img src="<?php echo base_url() ?>assets/images/basket-banner.png">
                </div>
                <div class="item">
                    <img src="<?php echo base_url() ?>assets/images/basket-banner.png">
                </div>
                <div class="item">
                    <img src="<?php echo base_url() ?>assets/images/basket-banner.png">
                </div>
            </div>
        </div>
    </div>
    <!--Slider Banner Wrap Close-->
    <!--Center Block Wrapper-->
    <div class="container">
        <div class="center-block-wrapper clearfix">
            <!--Breadcrumb-->
            <div class="bread-crumb-wrap">
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Basketball </a></li>

                </ul>
            </div>
            <!--Breadcrumb Close-->
            <!--Tab Wrapper-->