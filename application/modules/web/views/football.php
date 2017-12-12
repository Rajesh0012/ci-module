<?php require_once 'slider.php'; ?>
<div class="container">
            <div class="center-block-wrapper clearfix">
              <!--Breadcrumb-->   
              <div class="bread-crumb-wrap">
                        <ul>
                            <li><a href="javascript:void(0)">Home</a></li>
                            <li><a href="javascript:void(0)">Football </a></li>
                            
                        </ul>
                    </div>
            <!--Breadcrumb Close-->
               <!--Tab Wrapper-->
            <div class="tab-wraper clearfix">
                <ul class="clearfix">
                    <li><a href="javascript:void(0)" onclick="getData('<?php echo base64_encode('all'); ?>','Football','<?php echo $_GET['id']; ?>')" data-in="#tab1" class="<?php if(empty($this->input->get('tour_id'))) echo 'active'; ?>"><?php echo 'Football'; ?></a></li>
                    <?php if(!empty($tournament_arr))
                    {
                        foreach($tournament_arr as $tour){
                            ?>
                            <li><a class="<?php if($tour['tournament_id'] == base64_decode($this->input->get('tour_id'))){ echo 'active';} ?>" href="javascript:void(0)" onclick="getData('<?php echo base64_encode($tour['tournament_id']); ?>','<?php echo $tour['sport_name']; ?>','<?php echo $_GET['id']; ?>')" data-in="#tab1"><?php echo $tour['tournament_name']; ?></a></li>
                            <?php

                        }

                    } ?>


                    <!--<li><a href="javascript:void(0)" data-in="#tab4" class="">More >> </a></li>!-->

                </ul>
                     </div>     
                        
                <!--Tap Wrapper Close-->
               <div class="commn-col-wrap clearfix">
                  <!--left col -->
                  <div class="game-bet-col">
                     <div class="inner-game-bet-col">
                        <div class="header weekly-game">
                              <h2 class="heading">Football Odds</h2>
                              <span class="game-date">Football Matches</span>
                        </div>
                         <?php if(isset($res_arr) && !empty($res_arr)): foreach ($res_arr as $key=>$values): ?>
                        <div class="weekly-game-list-wrap clearfix">
                           <h2 class="prev-date-wrap"><?php echo date('D, d M Y',strtotime($values['schedule_r_date'])); ?></h2>
                            <ul class="cricket-game-li">
                            <?php foreach ($values['detail_arr'] as $listkey=>$listvalues):  ?>


                              <li class="clearfix">
                                 <div class="wk-time-game-col">
                                    <span class="time-view-state-wrap">
                                    <span class="time-state"><?php echo $listvalues['match_time']; ?></span>
                                    <span class="view-state"><a href="javascript:void(0)">View Game Stats</a></span>
                                    </span>
                                 </div>
                                  <?php  if(isset($listvalues['comptitors']) && !empty($listvalues['comptitors'])){ $comp=explode(',',$listvalues['comptitors']); }?>
                                 <div class="team-vsgame-col">
                                        <div class="compare-team">
                                        <span class="team-name"><?php echo isset($comp[0]) && !empty($comp[0])?$comp[0]:''; ?></span>
                                    <span class="vs">Vs</span>
                                    <span class="team-name"><?php echo isset($comp[1]) && !empty($comp[1])?$comp[1]:''; ?></span>
                                        </div>
                                     <?php

                                     if($listvalues['market_name'] == '3way'):
                                         $exp=explode(',',$listvalues['type']);
                                         $odds=explode(',',$listvalues['odds']);

                                         ?>

                                         <span class="team-name"><span class="plus-icon"></span> <span class="game-tm-name" ><?php echo !empty($exp[0])?$exp[0]:''; ?></span>  <span class="score-rate">(<?php echo !empty($odds[0])?$odds[0]:''; ?>)</span></span>
                                         <span class="team-name"><span class="plus-icon"></span> <span class="game-tm-name" > <?php echo !empty($exp[2])?$exp[2]:''; ?></span>   <span class="score-rate">(<?php echo !empty($odds[2])?$odds[2]:''; ?>)</span></span>
                                         <span class="team-name"><span class="plus-icon"></span><span class="game-tm-name" ><?php echo !empty($exp[1])?$exp[1]:''; ?> </span>  <span class="score-rate">(<?php echo !empty($odds[1])?$odds[1]:''; ?>)</span></span>

                                         <?php
                                         $exp1=explode(',',$listvalues['type']);
                                         $odds1=explode(',',$listvalues['odds']);

                                     else: ?>

                                         <span class="team-name"><span class="plus-icon"></span> <span class="game-tm-name" ><?php echo isset($exp1[0]) && !empty($exp1[0])?$exp1[0]:''; ?></span>  <span class="score-rate"><?php echo isset($odds1[0]) && !empty($odds1[0])?$odds1[0]:''; ?></span></span>

                                         <span class="team-name"><span class="plus-icon"></span><span class="game-tm-name" ><?php echo isset($exp1[1]) && !empty($exp1[1])?$exp1[1]:''; ?> </span>  <span class="score-rate"><?php echo isset($odds1[1]) && !empty($odds1[1])?$odds1[1]:''; ?></span></span>

                                     <?php endif; ?>

                                    </div>
                                 <div class="view-tips-odd-col text-center">
                                    <ul>
                                       <li><button onclick="return window.location.href='<?php echo base_url() ?>web/Home/match_odds?matchId=<?php echo base64_encode($listvalues['match_id']).'&id='.base64_encode($listvalues['id']).'&market_name='.base64_encode($listvalues['market_name']).'&sport_name=football&link_id='.$this->input->get('id');  ?>'" class="view-od-tps-btn">View Odds</button></li>
                                       <li><button class="view-od-tps-btn">View Tips</button></li>
                                    </ul>
                                 </div>
                              </li>


                            <?php endforeach; ?>
                            </ul>
                        </div>

                    <?php endforeach;else:?>

                             <ul>
                            <span class="not_found">
                                     No Results Found
                                 </span>

                             </ul>
                    <?php   endif; ?>

                     </div>
                  </div>
                  <!--left col Wrap -->
                  <!--Right Add and Free Tips col -->
               <?php require_once 'sidebar.php'; ?>
         <!--Center Block Wrapper close-->
         <!--footer-->
              </div>
        <!--Bottom add banner-->
        <div class="bottom-banner-wrap">
            <figure>
                <img src="<?php echo BASE_URL . '/web/' ?>images/bottom-banner-cricket.png">
            </figure>
        </div>
        <!--Bottom add banner close-->
    </div>
</div>
         <footer>
            <!--nav footer wrap --> 
            <div class="footer-wrap">
               <div class="container">
                  <div class="row">
                     <div class="col-sm-4">
                        <div class="inner-col-footer">
                           <h3 class="title"><a href="javascript:void(0)">
                              <img src="<?php echo BASE_URL . '/web/' ?>images/logo.svg">   
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
                                       <li><a href="javascript:void(0)">Football</a></li>
                                       <li><a href="javascript:void(0)">Rugby</a></li>
                                       <li><a href="javascript:void(0)">Tennis</a></li>
                                       <li><a href="javascript:void(0)">Basketball</a></li>
                                       <li><a href="javascript:void(0)">Football</a></li>
                                       <li><a href="javascript:void(0)">Cricket</a></li>
                                       <li><a href="javascript:void(0)">Golf</a></li>
                                       <li><a href="javascript:void(0)">Boxing</a></li>
                                    </ul>
                                 </div>
                              </div>
                              <div class="col-xs-6">
                                 <div class="sports-col">
                                    <h3 class="title">COMPANY</h3>
                                    <ul>
                                       <li><a href="javascript:void(0)">About Us</a></li>
                                       <li><a href="javascript:void(0)">Jackpot</a></li>
                                       <li><a href="javascript:void(0)">Free Statistics</a></li>
                                       <li><a href="javascript:void(0)">Free Tips</a></li>
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
                                 <li><a href="javascript:void(0)">
                                    <img src="<?php echo BASE_URL . '/web/' ?>images/play-store.png" alt="Play Store">    
                                    </a>
                                 </li>
                                 <li>
                                    <a href="javascript:void(0)">
                                    <img src="<?php echo BASE_URL . '/web/' ?>images/app-store.png" alt="App Store">    
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