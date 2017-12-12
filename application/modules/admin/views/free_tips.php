<style>
    .tip_msg{
    position: absolute;
    left: 414px;
    }
</style>

<div class="inner-right-panel">
               <!--breadcrumb wrap-->  
               <div class="breadcrumb-wrap">
                  <ul>
                     <li><a href="javascript:void(0);">Free Tips</a></li>
                  </ul>
               </div>

               <!--breadcrumb wrap close-->    
               <!--Filter Section -->

               <div class="fltr-srch-wrap clearfix">
               <?php echo form_open('',array('method'=>'get','name'=>'tips_form','id'=>'tips_form')); ?>
                   <input type="hidden" id="sort" name="sort">
                  <div class="left-lg-col">
                     <div class="row">
                        <div class="col-lg-8">
                           <div class="srch-wrap">
                              <span onclick="return window.location.href='<?php echo base_url() ?>/admin/freetips'" class="srch-close-icon <?php if(!empty(trim($search))){ echo 'show-srch'; } ?>"></span>

                              <button type="submit" class="search-icon "></button>
                              <input type="text" value="<?php if(!empty(trim($search))) { echo $search;} ?>" name="search" placeholder="Sport Name,Tips title" class="srch-box">
                           </div>
                        </div>
                     </div>
                  </div>

                  <div class="right-lg-col">
                     <div class="top-opt-wrap text-right">
                        <ul>
                           <li><a href="javascript:void(0)" title="File Export"><img src="<?php echo BASE_URL . '/admin/' ?>images/export-file.svg"> </a></li>
                           <li><a href="javascript:void(0)" title="Filter" id="filter-side-wrapper"><img src="<?php echo BASE_URL . '/admin/' ?>images/filter.svg"></a></li>
                           <li><button type="button" class="commn-btn add-bttn" title="Add Tips" data-toggle="modal" data-target="#myModal-addtips">Add Tips</button></li>
                        </ul>
                     </div>
                  </div>
               </div>
               <!--Filter Section Close-->
               <!--Filter Wrapper-->
          <?php $searchselected=$this->input->get();  ?>
               <div class="filter-wrap">
                  <h2 class="fltr-heading">Filter </h2>
                  <div class="inner-filter-wrap">
                     <div class="fltr-field-wrap">
                        <label class="admin-label">Sports Name</label>
                        <div class="commn-select-wrap">
                           <select name="sports" class="selectpicker">
                              <option value="">All</option>
                              <?php foreach ($sports as $sport): ?>
                               <option <?php if(isset($searchselected['sports']) && !empty(trim($searchselected['sports'])) && $searchselected['sports'] == $sport->sport_name ) { echo 'selected';}?> value="<?php echo $sport->sport_name; ?>"><?php echo $sport->sport_name	; ?></option>
                             <?php endforeach; ?>
                           </select>

                        </div>
                     </div>
                     <div class="fltr-field-wrap">
                        <label class="admin-label">Type</label>
                        <div class="commn-select-wrap">
                           <select name="game_type" class="selectpicker">
                              <option value="">All</option>
                              <option <?php if(isset($searchselected['game_type']) && $searchselected['game_type'] == 1) { echo 'selected';} ?> value="1">Game</option>
                              <option <?php if(isset($searchselected['game_type']) && $searchselected['game_type'] == 2) { echo 'selected';} ?> value="2">Match</option>
                           </select>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-xs-6">
                           <div class="fltr-field-wrap">
                              <label class="admin-label">From</label>
                              <div class="input-group date" id="datetimepicker1">
                                 <input name="date_from" type="text" id="datetime1" value="<?php if(isset($searchselected['date_from']) && !empty($searchselected['date_from'])){ echo $searchselected['date_from']; } ?>" placeholder="From">
                                 <span class="input-group-addon">
                                 <span class="glyphicon glyphicon-calendar"></span>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <div class="col-xs-6">
                           <div class="fltr-field-wrap">
                              <label class="admin-label">To</label>
                              <div class="input-group date" id="datetimepicker2">
                                 <input name="date_to" type="text" id="datetime1" value="<?php if(isset($searchselected['date_to']) && !empty($searchselected['date_to'])){ echo $searchselected['date_to']; } ?>" placeholder="From">
                                 <span class="input-group-addon">
                                 <span class="glyphicon glyphicon-calendar"></span>
                                 </span>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="button-wrap text-center">
                        <input onclick="return window.location.href='<?php echo base_url() ?>admin/freetips'" type="reset" value="Reset" name="" class="commn-btn cancel">
                        <input type="submit" value="Filter" name="" class="commn-btn save">
                     </div>
                  </div>
               </div>
               <!--Filter Wrapper Close-->
               <div class="display-wrap clearfix">
                  <div class="left-column">

                     <div class="display">
                        <select onchange="return $('#tips_form').submit()" name="display" class="selectpicker">
                            <option <?php if(isset($display) && !empty($display) && $display == DISPLAY1){ echo 'selected';} ?> value="<?php echo DISPLAY1; ?>">Display 5</option>
                            <option <?php if(isset($display) && !empty($display) && $display == DISPLAY2){ echo 'selected';} ?> value="<?php echo DISPLAY2; ?>">Display 10</option>
                            <option <?php if(isset($display) && !empty($display) && $display == DISPLAY3){ echo 'selected';} ?> value="<?php echo DISPLAY3; ?>">Display 15</option>

                        </select>
                     </div>
                  </div>
                  <div class="right-column">
                      <span style="color: green; font-weight: bold;font-size:18px" id="msgarea"></span>
                  <img style="display: none" id="process" src="<?php echo base_url() ?>public/admin/images/Processing.gif">
                  </div>
               </div>

          <span class="tip_msg"><?php $i=$page; if($this->session->flashdata('msg')){ echo $this->session->flashdata('msg');} ?></span>
               <!--Table-->

          <?php if($records_found>0) :?>
              <span> <?php $k=1; echo (++$page) ?> - <?php echo ($i)+ (count($list['result'])).' of '. $records_found; ?></span>
          <?php endif; ?>
               <div class="white-wrapper">
                  <div class="table-responsive clearfix">
                     <!--table div-->
                      <script>
                          function sort(str) {

                              $('#sort').attr('value',str);
                              $('#tips_form').submit()
                          }
                      </script>
                     <table id="myTable" class="list-table table " cellspacing="0" width="100%">
                        <thead>
                           <tr>
<!--                              <th>
                                  <div class="th-checkbox">
                                      <input  class="filter-type filled-in" type="checkbox" name="filter" id="flowerss" value="flowers">
                                      <label for="flowers" class="lbl-check"><span>Delete</span></label>
                                  </div>
                                  <i id="showtrash" style="display: none" class="fa fa-trash" title="Delete" aria-hidden="true" data-toggle="modal" data-target="#myModal-del-trash"></i>
                     </th>-->
                               </th>
                              <th>S.No.</th>
                              <th>Manage Priority</th>
                              <th>Title</th>
                              <th> Sport Name
                                  <?php if(isset($_GET['sort']) && $this->input->get('sort') == 'sportdesc'): ?>
                                      <i onclick="sort('sportasc')" class="fa fa-sort-asc" aria-hidden="true"></i>
                                  <?php elseif(isset($_GET['sort']) && $this->input->get('sort') == 'sportasc'): ?>

                                      <i onclick="sort('sportdesc')" class="fa fa-sort-desc" aria-hidden="true"></i>
                                  <?php else: ?>
                                      <i onclick="sort('sportasc')" class="fa fa-sort-asc" aria-hidden="true"></i>
                                      <i onclick="sort('sportdesc')" class="fa fa-sort-desc" aria-hidden="true"></i>

                                  <?php endif; ?>
                              </th>
                              <th>Type </th>
                              <th>Tournamnent </th>
                              <th>Status </th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                        <?php  if(isset($list['result']) && !empty($list['result'])): $chk=2; foreach ($list['result'] as $key=>$values): ?>
                           <tr class="text-center item">
<!--                              <td>
                                  <div class="th-checkbox">
                                      <input class="filter-type filled-in checked" type="checkbox" name="delete[]" id="flowers<?php echo $chk; ?>" value="<?php echo $values->id; ?>">
                                      <label for="flowers<?php echo $chk++; ?>" class="lbl-check"><span></span></label>
                                  </div>
                              </td>-->
                              <td><?php echo ++$i; ?></td>
                                  <td><select name="setPriorities|+|<?php echo  $values->id; ?>" id="setPriorities<?php echo $i; ?>" onchange="setpriorities(this.id,this.value,this.name)" onmouseover="selectedValue(this.id,this.value)" class="form-control">
                                      <?php for($num=1;$num<=$records_found;$num++) {?>
                                          <option <?php if($values->priorities == $num ){ echo 'selected';} ?> value="<?php echo $num ?>"><?php echo $num; ?></option>
                                        <?php } ?>
                                    </select>

                                  </td>
                              <td>
                                 <span class="td-text-wrap">
                                     <?php if($values->type == 1)
                                            {
                                     ?>
                                     <a title="<?php echo $values->title; ?>" href="<?php echo base_url() ?>admin/Tips/tipsDetails?id=<?php echo base64_encode($values->id); ?>&per_page=<?php echo $this->input->get('per_page'); ?>"><?php echo $values->title; ?> </a>
                                     <?php
                                                
                                            }else
                                            {
                                  ?>
                                  <a title="<?php echo $values->title; ?>" href="<?php echo SITE_URL ?>/admin/Tips/feature_tips_details?id=<?php echo base64_encode($values->id); ?>&per_page=<?php echo $this->input->get('per_page'); ?>"><?php echo $values->title; ?> </a>
                                  <?php
                                                
                                            } 
                                      ?>
                               
                              </td>
                              <td><?php echo $values->sport_name; ?> </td>
                              <td><?php if($values->type == 1){ echo 'Games';}else{ echo 'Features';} ?> </td>
                              <td><?php if(empty($values->tournament_name)){ echo '-';}else{ echo $values->tournament_name; } ?></td>
                              <td><?php  if($values->status  == TIPS_ACTIVE){ echo 'active';}else{ echo 'inactive';}; ?> </td>
                              <td class="text-center">
                                  <?php if($values->type == 1){ 
                                  ?>
                                  <a href="<?php echo SITE_URL; ?>/admin/games?id=<?php echo base64_encode($values->id); ?>"><i class="fa fa-pencil" title="Edit" aria-hidden="true"></i>  </a>
                                  <a data-toggle="modal" onclick="return $('.getID').attr('id','<?php echo base64_encode($values->id); ?>')" data-target="#myModal-del-trash" ><i class="fa fa-trash" title="Edit" aria-hidden="true"></i>  </a>
                                  
                                      <?php 
                                       }else
                                    { 
                                  ?>
                                      <a href="<?php echo SITE_URL; ?>/admin/tips/edit_feature_tips?id=<?php echo base64_encode(trim($values->id)); ?>"><i class="fa fa-pencil" title="Edit" aria-hidden="true"></i>  </a>
                                      <a data-toggle="modal" onclick="return $('.getID').attr('id','<?php echo base64_encode($values->id); ?>')" data-target="#myModal-del-trash"><i class="fa fa-trash" title="Edit" aria-hidden="true"></i>  </a>
                                  
                                          <?php
                                  } ?>
                                 
                              </td>
                           </tr>
                        <?php   endforeach; else: ?>
                        <tr><td align="center" colspan="9">No Record Found!</td></tr>
                        <?php endif; ?>
                        </tbody>
                     </table>
                  </div>
                   <input type="hidden" id="prevalue"/>
               </div>
               <!--Table listing-->  


            <!-- Pagenation and Display data wrap-->
      <div class="bottom-wrap clearfix">
          <div class="left-column">
          </div>
          <div class="right-column text-right">
              <div class="pagenation-wrap">
                  <ul>
                      <?php

                      if(isset($pagination) && !empty($pagination)){
                          echo $pagination;
                      }


                      ?>

                  </ul>
              </div>
          </div>
      </div>
            <!-- Pagenation and Display data wrap-->

      <!--data  Wrap close-->
      <!--Delete  Modal Close-->
      <!-- Modal -->
      <!-- Modal -->
      
      <div id="myModal-del-trash" class="modal fade" role="dialog">
         <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header modal-alt-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title modal-heading">Delete </h4>
               </div>
               <div class="modal-body">
                  <p class="modal-para">Are you sure want to delete this tips?</p>
                  <div class="button-wrap">
                     <input type="button" data-dismiss="modal" value="Cancel" class="commn-btn cancel" name="">
                     <input type="button" onclick="deleteId(this.id)" id=''  value="Delete" class="commn-btn save getID" name="">
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!--delete Modal Close-->
      <!--Block  Modal Close-->
      <!-- Modal -->
      <!-- Modal -->
      <div id="myModal-block" class="modal fade" role="dialog">
         <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header modal-alt-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title modal-heading">BLOCK </h4>
               </div>
               <div class="modal-body">
                  <p class="modal-para">Are you sure want to block this user?</p>
                  <div class="button-wrap">
                     <input type="button" value="Cancel" class="commn-btn cancel" name="">
                     <input type="button" value="Delete" class="commn-btn save" name="">
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php echo form_close(); ?>
      <!--block Modal Close-->
      <!-- Modal -->
      <div id="myModal-addtips" class="modal fade" role="dialog">
         <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header modal-alt-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title modal-heading">Add New Tips </h4>
               </div>
                <span id="msid"></span>
               <div class="modal-body">
                  <label class="admin-label">You want  to add tips for:</label>
                  <div class="button-wrap">
                     <ul>
                        <li>
                           <div class="radio-us">
                              <input class="filter-type filled-in" type="radio" name="tipslect" id="game" value="Game">
                              <label for="game" class="radio-checkin"><span></span>Game</label>
                           </div>
                        </li>
                        <li>
                           <div class="radio-us">
                              <input class="filter-type filled-in" type="radio" name="tipslect" id="featuredmatch" value="Featured Match">
                              <label for="featuredmatch" class="radio-checkin"><span></span>Featured Match</label>
                           </div>
                        </li>
                     </ul>
                  </div>
                  <div class="button-wrap text-center">
                     <!-- <input type="button" value="Cancel" class="commn-btn cancel" name=""> -->
                     <input type="button" value="Next" class="commn-btn" name="match_type" id="match_type">
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!--block Modal Close-->
      <script>
      function deleteId(str){
         
          window.location.href="<?php echo SITE_URL; ?>/admin/tips/deleteRow?delete="+str;
      }
      </script>







