
       
        <?php require_once 'slider.php'; ?>
         <!--Slider Banner Wrap Close-->
         <!--Center Block Wrapper-->  
         <div class="container">
            <div class="center-block-wrapper clearfix">
               <div class="commn-col-wrap clearfix">
                  <!--left col -->
                  <div class="game-bet-col">
                     <div class="inner-game-bet-col">
                        <div class="header weekly-game">
                           <h2 class="heading">Popular Bets</h2>

                        </div>
                        <div class="weekly-game-list-wrap clearfix">
                           <ul id="appends">
                               <?php if(isset($allList) && !empty($allList)): foreach ($allList as $key=>$values): ?>
                              <li class="clearfix">
                                 <div class="wk-time-game-col">
                                    <span class="week-game-icon foot-ball" style="background-image:url(<?php
                                    if('sr:sport:2'== $values->sport_id){ echo base_url().'public/web/images/basket-bal-b.png'; }
                                    elseif ('sr:sport:5' == $values->sport_id){ echo base_url().'public/web/images/tennis-b.png'; }
                                    elseif ('sr:sport:9'== $values->sport_id){ echo base_url().'public/web/images/golf-b.png'; }
                                    elseif ('sr:sport:10'== $values->sport_id){ echo base_url().'public/web/images/boxing_active.svg';}
                                    elseif ('sr:sport:12'== $values->sport_id){ echo base_url().'public/web/images/rugby_active.svg';}
                                    elseif ('sr:sport:16'== $values->sport_id){ echo base_url().'public/web/images/football-b.svg';}
                                    elseif ('sr:sport:21'== $values->sport_id){ echo base_url().'public/web/images/cricket-b.png';}
                                    else { echo base_url().'public/web/images/football-b.svg';} ?>)">
                                    </span>
                                    <span class="time-view-state-wrap">
                                   <!-- <span class="time-state"><?php /*if(isset($values->scheduled) && !empty($values->scheduled)){ echo date('Y-m-d h:i:s a',strtotime($values->scheduled));}else{ echo 'N/A';} */?></span>-->
                                    <!--<span class="view-state"><a href="javascript:void(0)">View Game Stats</a></span>-->
                                    </span>
                                 </div>
                                 <div class="team-vsgame-col">
                                    <span class="meain-team"><?php  if(isset($values->name) && !empty($values->name)){ echo $values->name;}else{ echo 'N/A';} ?></span>
                                     <!--<a href="javascript:void(0)">--><span class="team-name"><?php if(isset($values->season_name) && !empty($values->season_name)){ echo $values->season_name;}else{ echo 'N/A';} ?></span><!--</a>-->

                                 </div>
                                 <div class="view-tips-odd-col">
                                    <ul>
                                        <?php $match_id = isset($values->match_id)?$values->match_id:''; ?>
                                        <li><button onclick="return window.location.href='<?php echo base_url() ?>web/Home/view_odds?id=<?php echo base64_encode($values->tournament_id);  ?>'" class="view-od-tps-btn">View Odds</button></li>
                                       <li><button class="view-od-tps-btn">View Tips</button></li>
                                       <li><span class="fraction"><?php if(isset($values->odds) && !empty($values->odds)){echo $values->odds;}else{ echo 'N/A';}  ?></span></li>
                                        <li>  <figure style="background-image: url('<?php
                                            if('sr:book:7'== $values->bookies_id){ echo base_url().'public/web/images/William-Hill.png'; }
                                            elseif ('sr:book:6' == $values->bookies_id){ echo base_url().'public/web/images/Kambi2.png'; }
                                            elseif ('sr:book:12'== $values->bookies_id){ echo base_url().'public/web/images/bwin-list.png'; }
                                            elseif ('sr:book:28'== $values->bookies_id){ echo base_url().'public/web/images/bet-at-home.png';}
                                            elseif ('sr:book:74'== $values->bookies_id){ echo base_url().'public/web/images/bet-365-list.png';}
                                            elseif ('sr:book:459'== $values->bookies_id){ echo base_url().'public/web/images/SBObet.png';}
                                            elseif ('sr:book:988'== $values->bookies_id){ echo base_url().'public/web/images/188bet-list.png';}
                                            elseif ('sr:book:9'== $values->bookies_id){ echo base_url().'public/web/images/ladbrokes.png';}
                                            elseif ('sr:book:10643'== $values->bookies_id){ echo base_url().'public/web/images/BetfairFeedsNXT.png';}
                                            else { echo base_url().'public/web/images/image-n-a.png';}?>')"></figure></li>
                                    </ul>
                                 </div>
                              </li>

                              
                               <?php endforeach;?>
                               <?php else: ?>
                                   <ul id="loaddata">

                                       <li class="clearfix">
                                           No Results Found
                                       </li>

                                   </ul>


                               <?php endif; ?>
                             </ul>
                            <?php if($count >WEB_RECORDS_PER_PAGE): ?>
                            <ul id="loaddata">

                                <li class="clearfix">
                                    <span id="<?php echo $this->input->get('id'); ?>" onclick="loadMore(this.id)" class="list-view"><a href="javascript:void(0)">View More</a></span>
                                </li>

                            </ul>
                            <?php endif; ?>
                        </div>

                     </div>
                  </div>
                  <!--left col Wrap -->
                  <!--Right Add and Free Tips col -->
                  <?php require_once 'sidebar.php'; ?>
                  <!--Right Add and Free Tips Wrap -->
               </div>
               <!--Bottom add banner-->  
               <div class="bottom-banner-wrap">
                  <figure>
                     <img src="<?php echo BASE_URL . '/web/' ?>images/bottom-banner.png">
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
                                       <li><a href="javascript:void(0)">Contact Us</a></li>
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

