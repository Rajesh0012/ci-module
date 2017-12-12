<div class="inner-right-panel">
               <!--breadcrumb wrap-->  
               <div class="breadcrumb-wrap">
                  <ul>
                     <li><a href="javascript:void(0)">Free Tips </a></li>
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
                               <span class="error">
                                   <?php  $errorMsg=$this->session->flashdata('msg');
                                   if(isset($errorMsg) && !empty($errorMsg)){ echo $errorMsg;} ?>
                                   
                                </span> 
                              <ul>
                                 <li><span class="stp-digit">Step 1 </span> </li>
                                 <li><span class="nxt-step">Step 2</span> <span class="heading">Add Tip</span></li>
                              </ul>
                           </div>
                        </div>
                     </div>
                     <h2 class="top-heading"><?php if(!empty($detail_arr['competitor_name'])){ echo $detail_arr['competitor_name']; } ?>-<?php if(!empty($detail_arr['scheduled'])){ echo $detail_arr['scheduled']; } ?></h2>
                     <form action="" method="Post" id="add_tip" enctype="multipart/form-data">
                     <div class="row m-t-sm">
                        <div class="col-sm-4">
                           <figure class="usr-dtl-pic">
                               <div class="post_gallery">
                                   
                               </div>
                              <label class="camera" for="upload-img"><i class="fa fa-camera" aria-hidden="true"></i></label>
                              <input type="file" accept="image/png,image/jpg,image/jpeg" name="image" id="upload-img" style="display:none;">
                           </figure>
                        </div>
                        <div class="col-sm-6">
                           <div class="row admin-filed-wrap">
                              <div class="col-xs-4">
                                 <label class="admin-label">Title<mark>*</mark></label> 
                              </div>
                              <div class="col-xs-8">
                                 <div class="field-wrap">
                                    <input type="text" name="title" value="">
                                 </div>
                              </div>
                           </div>
                            <div class="row admin-filed-wrap">
                              <div class="col-xs-4">
                                 <label class="admin-label">Short Description<mark>*</mark></label> 
                              </div>
                              <div class="col-xs-8">
                                 <div class="field-wrap">
                                     <input type="text" name="short_desc" value=""></textarea>
                                 </div>
                              </div>
                           </div>
                            <input type="hidden" name="match_id" value="<?php echo base64_decode($_GET['mid']); ?>">
                            <input type="hidden" name="book_id" value="<?php echo base64_decode($_GET['book']); ?>">
                           <div class="row admin-filed-wrap">
                              <div class="col-xs-4">
                                 <label class="admin-label">Status<mark>*</mark> </label> 
                              </div>
                              <div class="col-xs-8">
                                 <div class="field-wrap">
                                    <span class="custom-cart"></span> 
                                    <select name="status" class="form-control">
                                       <option value="1">Active</option>
                                       <option value="2">Inactive</option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <h2 class="top-heading m-t-sm">Tips For Selection</h2>
                     <?php if(!empty($data_arr)){
    
                             $i=1;
                            foreach($data_arr as $key=>$data)
                            {
                    ?>
                     <div class="row">
                        <div class="col-xs-12">
                           <div class="tip-selection-wrap clearfix m-t-sm">
                              <div class="col-left">
                                 <div class="ad-checkbox">
                                    <!--<input class="filter-type filled-in" type="checkbox" name="filter" id="flowers" value="flowers">!-->
                                    <label for="flowers" class="subadmin-lbl-check"><span></span><?php echo !empty($data)?trim($data):'N/A'; ?>- <?php echo !empty($key)?trim($key):'N/A'; ?></label>
                                 </div>
                              </div>
                              <div class="col-right text-right">
                                 <div class="display">
                                    <select name="position[]" class="selectpicker">
                                       <option value="1" <?php if($i=='1') { echo "selected";}?>>1</option>
                                       <option value="2" <?php if($i=='2') { echo "selected";}?>>2</option>
                                       <option value="3" <?php if($i=='3') { echo "selected";}?>>3</option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" name="odd[]" value="<?php echo !empty($key)?trim($key):'N/A'; ?>">
                           <div class="description-wrap-tip">
                              <textarea name="desc[]" class="descp-textarea"></textarea>
                           </div>
                        </div>
                     </div>
                     <?php
                                
                          $i++;  }
                     } ?>
                     
                     <div class="button-wrap text-center">
                        <input type="reset" value="Clear" name="" class="commn-btn cancel">
                        <input type="Submit" value="Add Tip" name="" class="commn-btn save">
                     </div>
                  </form>
                  </div>
               </div>
               <!--Account-details wrapper close-->
            </div>
         </div>
      </div>