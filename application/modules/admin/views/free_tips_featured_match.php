<div class="inner-right-panel">
               <!--breadcrumb wrap-->  
               <div class="breadcrumb-wrap">
                  <ul>
                     <li><a href="<?php echo base_url() ?>/admin/freetips">Free Tips </a></li>
                     <li><a href="javascript:void(0)">Add New </a></li>
                  </ul>
               </div>
               <!--breadcrumb wrap close-->  
               <!--Account-details wrapper-->   
               <div class="white-wrapper">
                  <span class="top-sticker">Featured Match</span>
                  <h2 class="top-heading">Add tips for featured matches</h2>
                  <div class="wrap">
                     <div class="row">
                        <div class="col-sm-12">
                           <div class="add-pets-stepwrap text-center">
                               <span class="success">
                                      <?php  $errorMsg=$this->session->flashdata('msg');
                                   if(isset($errorMsg) && !empty($errorMsg)){ echo $errorMsg;} ?>
                       </span>
                              <ul>
                                 <li><span class="stp-digit">Step 1 </span> <span class="heading">Select Game</span> </li>
                                 <li><span class="nxt-step">Step 2</span> </li>
                              </ul>
                           </div>
                        </div>
                        <div class="col-sm-12 m-t-sm">
                           <div class="table-responsive clearfix">
                              <!--table div-->
                              <table id="example" class="list-table table " cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th>Time</th>
                                       <th>Home</th>
                                       <th>Draw</th>
                                       <th>Away</th>
                                       <th>Betting </th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                     <?php if(!empty($sport_event_arr)){
                                         
                                         foreach($sport_event_arr as $sevent)
                                         {
                                             
                                     ?>
                                     <tr>
                                       <td colspan="5" class="date-time-td">
                                          <span class="date-time"><?php echo $sevent['schedule_date'] ?></span>
                                       </td>
                                    </tr>
                                    <!--<tr>
                                       <td colspan="5" class="date-time-td">
                                          <span class="type-match">English Premier League Matches</span>
                                       </td>
                                    </tr>!-->
                                     <?php
                                     
                                       if(!empty($sevent['detail_arr']))
                                       {
                                           foreach($sevent['detail_arr'] as $eventDetail){
                                               
                                               $newArr=array();
                                               $typeArr=explode(',', $eventDetail['type']);
                                               $oddsArr=explode(',', $eventDetail['odds']);
                                               $newArr=array_combine($typeArr, $oddsArr);
                                               krsort($newArr);
                                                         
                                               //print_r($newArr); die; 
                                      ?>
                                    <tr class="text-center">
                                       <td>
                                          <span class="mtch-time"><?php echo !empty($eventDetail['match_time'])?$eventDetail['match_time']:''; ?></span>
                                          
                                       </td>
                                        <td>
                                          <span class="wrap-plus-bt-score">
                                          <span class="bet-score"><?php echo !empty($eventDetail['qualifiers']['home'])?$eventDetail['qualifiers']['home']:'N/A'; ?> <span class="friction-digit-td"><?php echo !empty($newArr['home'])?$newArr['home']:'';?></span></span>
                                          </span>
                                       </td>
                                       <td>
                                          <span class="wrap-plus-bt-score">
                                              
                                          <span class="bet-score">draw (<?php echo !empty($eventDetail['qualifiers'])?$eventDetail['qualifiers']['home'].' Vs'.$eventDetail['qualifiers']['away']:'N/A'; ?>) <span class="friction-digit-td"><?php echo !empty($newArr['draw'])?$newArr['draw']:'';?></span></span>
                                          </span>
                                       </td>
                                       <td>
                                          <span class="wrap-plus-bt-score">
                                          <span class="bet-score"><?php echo !empty($eventDetail['qualifiers']['away'])?$eventDetail['qualifiers']['away']:'N/A'; ?> <span class="friction-digit-td"><?php echo !empty($newArr['away'])?$newArr['away']:'';?></span></span>
                                          </span>
                                       </td>
                                       <td>
                                          <button class="commn-btn add-tips" onclick="addTipUrl('<?php echo base64_encode($eventDetail['id']); ?>','<?php echo base64_encode($eventDetail['book_id']); ?>')">Add Tips</button>
                                       </td>
                                    </tr>
                                      <?php
                                               
                                           }
                                           
                                           
                                       }
                                             
                                         }
                                         
                                     } ?>
                                    
                                    
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <!--Account-details wrapper close-->
            </div>
         </div>
      </div>