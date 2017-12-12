
<!--Add block section close-->

<!--Center Block Wrapper-->
<div class="container">
    <!--Breadcrumb-->
    <div class="bread-crumb-wrap">
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">All Sports </a></li>
            <li><a href="#"><?php if(isset($oddslist[0]->compitetor_name) && !empty($oddslist[0]->compitetor_name)){ echo $oddslist[0]->compitetor_name;}else{ echo 'N/A';} ?></a></li>
        </ul>
    </div>

    <!--Breadcrumb Close-->
    <div class="center-block-wrapper clearfix">

        <!--View odds team vs fillter wrapper-->
        <div class="top-filter-wrapper clearfix">
            <div class="left-col">
                                    <span class="view-odds-sport-icon" style="background-image: url('images/basket_ball_active.svg');">

                                    </span>
                <span class="match-team-wrap">
                                        <span class="team"><?php if(isset($oddslist[0]->compitetor_name) && !empty($oddslist[0]->compitetor_name)){ echo $oddslist[0]->compitetor_name;}else{ echo 'N/A';} ?></span><!--<span class="vs">v</span>  <span class="team">Liverpool</span>-->
                                        <span class="date-time-odds"> <?php if(isset($oddslist[0]->scheduled) && !empty($oddslist[0]->scheduled)) { echo  date('D, d M | h:i a',strtotime($oddslist[0]->scheduled));}else{ echo 'N/A';} ?></span>
                                        <span class="type-match"><?php if(isset($oddslist[0]->tournament_name) && !empty($oddslist[0]->tournament_name)) { echo  $oddslist[0]->tournament_name; }else{ echo 'N/A';} ?></span>
                                    </span>
            </div>

        </div>
        <!--View odds team vs fillter wrapper close-->


        <!--Tab Wrapper close-->


        <!--View ODDS  Table Wrapper-->

        <div class="view-odds-wrapper">
            <!--Table Odds-->
            <div class="table-responsive clearfix table-odds-wrapper">
                <!--table div-->
                <table id="example" class="list-table table home-bet-table-list" cellspacing="0" width="100%">
                    <thead>

                    </thead>
                    <tbody>
                    <tr class="text-center">
                        <td>&nbsp;</td>

                        <th><span class="img-bet-bookie"><img src="http://betcomparedev.applaurels.com/public/admin/images/32red.png"></span></th>

                        <th><span class="img-bet-bookie"><img src="http://betcomparedev.applaurels.com/public/admin/images/William-Hill.png"></span></th>

                        <th><span class="img-bet-bookie"><img src="http://betcomparedev.applaurels.com/public/admin/images/bwin.png"></span></th>

                        <th><span class="img-bet-bookie"><img src="http://betcomparedev.applaurels.com/public/admin/images/bet-at-home.png"></span></th>

                        <th><span class="img-bet-bookie"><img src="http://betcomparedev.applaurels.com/public/admin/images/bet-365.png"></span></th>

                        <th><span class="img-bet-bookie"><img src="http://betcomparedev.applaurels.com/public/admin/images/SBObet.png"></span></th>

                        <th><span class="img-bet-bookie"><img src="http://betcomparedev.applaurels.com/public/admin/images/unibet.png"></span></th>

                        <th><span class="img-bet-bookie"><img src="http://betcomparedev.applaurels.com/public/admin/images/ladbroker.png"></span></th>

                        <th><span class="img-bet-bookie"><img src="http://betcomparedev.applaurels.com/public/admin/images/bet-fair.png"></span></th>
                    </tr>

                    <?php if(isset($oddslist) && !empty($oddslist)) : foreach ($oddslist as $key=>$values): ?>
                    <tr class="text-center">
                        <td>
                            <span class="td-team"><?php echo $oddslist[$key]->compitetor_name ?></span>
                            <span class="lwd-wrap">
                                                     <ul>
                                                         <li><span>W</span></li>
                                                         <li><span class="red">L</span></li>
                                                         <li><span>W</span></li>
                                                         <li><span>W</span></li>
                                                         <li><span class="gray">D</span></li>
                                                         <li><span>W</span></li>
                                                     </ul>
                                                 </span>
                        </td>
                    <?php if(isset($values->odds)):
                        $exp=explode('|+|',$values->odds);
                        $exp2=explode('|+|',$values->bookies);
                    for($i=0;$i<=8;$i++)
                    {  $book=isset($exp2[$i])?$exp2[$i]:'';
                        if(in_array($book,$bookie_id)){ ?>
                        <td><span class="select-betodds"><a href="javascript:void(0)"><?php  if(isset($exp[$i])){  echo $exp[$i]; }else{ echo '-';}   ?></a></span></td>

                    <?php }else{ echo '<td>-</td>';}} endif; ?>

                    <?php endforeach; else:?>

                        <td colspan="10">No Records Found!</td>

                    <?php endif; ?>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!--Table Odds-->

<!--            <div class="view-odds-footer clearfix">
                <div class="left-col">

                </div>
                <div class="right-col">
                    <p class="text-disclaimer">
                        Odds shown come direct from online bookmakers. Please check all aspects of your bets before placement.
                    </p>
                    <div class="view-odds-indicator-wrapper">
                        <p>The best odds are <strong>Bold.</strong></p>
                        <span class="odds-shorting-wraper">
                                                        <span class="betting-caption">Odds shortening</span>
                                                    <span class="color-indicate light-blue"></span>
                                                </span>
                        <span class="odds-shorting-wraper">
                                                        <span class="betting-caption">Odds drifting</span>
                                                    <span class="color-indicate light-red"></span>
                                                </span>
                    </div>
                </div>
            </div>-->


        </div>
        <!--View ODDS  Table Wrapper Close-->





        <!--Latest Tips-->
        <div class="latest-tips-wrap">
            <div class="common-header">
                <h2 class="heading">
                    Latest Tips
                </h2>
            </div>

            <div class="latest-tips-block-wrap">
                <div class="inner-wrap">

                    <div id="latesttips-owl" class="owl-carousel">
                        <div class="item">
                            <!--Block Tips-->
                            <div class="block">
                                <figure class="tips-image" style="background-image:url('<?php echo base_url() ?>public/web/images/football-tips.png');">
                                </figure>
                                <p class="tips-short-nm">
                                    Premiere League Week 3
                                    Betting Tips
                                </p>
                            </div>
                            <!--Block Tips close-->
                        </div>
                        <div class="item">
                            <!--Block Tips-->
                            <div class="block">
                                <figure class="tips-image" style="background-image:url('<?php echo base_url() ?>public/web/images/basket-ball-2.png');">
                                </figure>
                                <p class="tips-short-nm">
                                    Premiere League Week 3
                                    Betting Tips
                                </p>
                            </div>
                            <!--Block Tips close-->
                        </div>
                        <div class="item">
                            <!--Block Tips-->
                            <div class="block">
                                <figure class="tips-image" style="background-image:url('<?php echo base_url() ?>public/web/images/football-3.png');">
                                </figure>
                                <p class="tips-short-nm">
                                    Premiere League Week 3
                                    Betting Tips
                                </p>
                            </div>
                            <!--Block Tips close-->
                        </div>
                        <div class="item">
                            <!--Block Tips-->
                            <div class="block">
                                <figure class="tips-image" style="background-image:url('<?php echo base_url() ?>public/web/images/football-4.png');">
                                </figure>
                                <p class="tips-short-nm">
                                    Premiere League Week 3
                                    Betting Tips
                                </p>
                            </div>
                            <!--Block Tips close-->
                        </div>



                        <div class="item">
                            <!--Block Tips-->
                            <div class="block">
                                <figure class="tips-image" style="background-image:url('<?php echo base_url() ?>public/web/images/football-tips.png');">
                                </figure>
                                <p class="tips-short-nm">
                                    Premiere League Week 3
                                    Betting Tips
                                </p>
                            </div>
                            <!--Block Tips close-->
                        </div>


                    </div>









                </div>
            </div>
        </div>
        <!--Latest Tips Close-->




        <!--Bottom add banner-->
        <div class="bottom-banner-wrap">
            <figure>
                <img src="<?php echo base_url() ?>public/web/images/bottom-add.png">
            </figure>
        </div>
        <!--Bottom add banner close-->
    </div>
</div>
<!--Center Block Wrapper close-->
<!--footer-->
<footer>
    <!--nav footer wrap -->
    <div class="footer-wrap">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <div class="inner-col-footer">
                        <h3 class="title"><a href="#">
                                <img src="<?php echo base_url() ?>public/web/images/logo.svg">
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
                                        <img src="<?php echo base_url() ?>public/web/images/play-store.png" alt="Play Store">
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="<?php echo base_url() ?>public/web/images/app-store.png" alt="App Store">
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
