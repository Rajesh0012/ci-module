    <!--<div class="inner-right-panel">-->
                 <!--breadcrumb wrap-->  
                 <div class="breadcrumb-wrap">
                        <ul>
                           <li><a href="<?php echo SITE_URL.'/admin/freetips' ?>">Free Tips </a></li>   
                           <li><a href="javascript:void(0);">Add Details</a></li>  
                        </ul>
                    </div>
                     <!--breadcrumb wrap close-->  

               <!--Account-details wrapper-->
        <?php if(isset($editdata->id) && !empty($editdata->id)):?>



               <form method="post" enctype="multipart/form-data" action="<?php echo base_url().'admin/games?id='?><?php echo isset($editdata->id)?base64_encode($editdata->id):'' ?>" id="tips_form">

       <?php  else: ?>

         <?php   echo form_open_multipart('',array('id'=>'tips_form'));
        endif;
?>

        <input type="hidden" name="<?php echo  $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash();?>" />
        <input type="hidden" value="<?php echo isset($editdata->id)?$editdata->id:''; ?>" name="updateId" >
               <div class="white-wrapper">
                  <span class="top-sticker">View Details</span>
                   <span class="error">
                        <?php  if(isset($msg) && !empty($msg)){ echo $msg;} ?>
                        <?php if(isset($exception) && !empty($exception)){ echo $exception;} ?>
                       </span>
                  <div class="wrap">
                     <div class="row">
                        <div class="col-sm-4">
                            <figure id="targetLayer" class="usr-dtl-pic">
                                <div class="post_gallery">
                                    <img id="placeholder" src="<?php if(isset($editdata->image) && !empty($editdata->image)){ echo $editdata->image;}else{ echo base_url() .'/public/admin/images/placeholder-user.png'; } ?>">
                                </div>
                                
                                <label class="camera" for="upload-img"><i class="fa fa-camera" aria-hidden="true"></i></label>
                                <input type="file"  accept="image/png,image/jpg,image/jpeg" name="tips_image" id="upload-img" style="display: none">

                            </figure>
                        </div>
                        <div class="col-sm-8">
                           <div class="row admin-filed-wrap">
                              <div class="col-sm-4">
                                 <label class="admin-label">Sport</label>
                              </div>
                              <div class="col-xs-6">
                                
                                   <div class="field-wrap">

                                       <?php ?>
                                       <span class="custom-cart"></span> 
                                   <select name="sports" class="form-control">
                                       <option value="">-select</option>
                                     
                                    <?php foreach ($sports as $key=>$values): ?>

                                        <option <?php if(isset($tempdata['sports']) && !empty($tempdata['sports']) && $tempdata['sports'] == $values->sport_id){ echo 'selected';}elseif (isset($editdata->sports) && !empty($editdata->sports) && $editdata->sports == $values->sport_id){ echo 'selected';}else{ echo '';} ?> value="<?php echo $values->sport_id; ?>"><?php echo $values->sport_name; ?></option>

                                    <?php endforeach; ?>
                                    </select>
                                   </div>
                                  <?php echo form_error('sports', '<div class="error">', '</div>'); ?>
                               </div>
                           </div>
                           <div class="row admin-filed-wrap">
                            <div class="col-sm-4">
                               <label class="admin-label">Status</label> 
                            </div>
                            <div class="col-xs-6">

                                <div class="field-wrap">
                                    <span class="custom-cart"></span> 
                                <select name="status" class="form-control">
                                    <option value="">--select--</option>
                                    <option <?php if(isset($tempdata['status']) && is_numeric($tempdata['status']) && in_array(TIPS_ACTIVE,$tempdata)){ echo 'selected';}elseif (isset($editdata->status) && is_numeric($editdata->status) && !empty($editdata->status) && $editdata->status == TIPS_ACTIVE){ echo 'selected';} ?> value="<?php echo TIPS_ACTIVE; ?>">active</option>
                                   <option <?php if(isset($tempdata['status']) && in_array(TIPS_INACTIVE,$tempdata)){ echo 'selected';}elseif (isset($editdata->status) && !empty($editdata->status) && $editdata->status == TIPS_INACTIVE){ echo 'selected';} ?> value="<?php echo TIPS_INACTIVE; ?>">Inactive</option>

                                </select>
                                </div>
                                <?php echo form_error('status', '<div class="error">', '</div>'); ?>
  
                            </div>

                         </div>
                           <!--   <div class="row admin-filed-wrap">
                            <div class="col-sm-4">
                                <label class="admin-label">Position</label>
                            </div>
                          <div class="col-xs-6">

                                <div class="field-wrap">
                                    <span class="custom-cart"></span>
                                    <select name="position" class="form-control">
                                        <option value="">--select--</option>
                                        <option <?php if(isset($tempdata['position']) && !empty($tempdata['position']) && $tempdata['position'] == POSITION1){ echo 'selected';} elseif (isset($editdata->position) && !empty($editdata->position) && $editdata->position == POSITION1) { echo 'selected';} ?> value="<?php echo POSITION1; ?>">1</option>
                                        <option <?php if(isset($tempdata['position']) && !empty($tempdata['position']) && $tempdata['position'] == POSITION2){ echo 'selected';} elseif (isset($editdata->position) && !empty($editdata->position) && $editdata->position == POSITION2) { echo 'selected';}  ?> value="<?php echo POSITION2; ?>">2</option>
                                        <option <?php if(isset($tempdata['position']) && !empty($tempdata['position']) && $tempdata['position'] == POSITION3){ echo 'selected';} elseif (isset($editdata->position) && !empty($editdata->position) && $editdata->position == POSITION3) { echo 'selected';}  ?> value="<?php echo POSITION3; ?>">3</option>
                                        <option <?php if(isset($tempdata['position']) && !empty($tempdata['position']) && $tempdata['position'] == POSITION4){ echo 'selected';} elseif (isset($editdata->position) && !empty($editdata->position) && $editdata->position == POSITION4) { echo 'selected';} ?> value="<?php echo POSITION4; ?>">4</option>
                                        <option <?php if(isset($tempdata['position']) && !empty($tempdata['position']) && $tempdata['position'] == POSITION5){ echo 'selected';} elseif (isset($editdata->position) && !empty($editdata->position) && $editdata->position == POSITION5) { echo 'selected';} ?> value="<?php echo POSITION5; ?>">5</option>
                                    </select>
                                </div>
                                <?php echo form_error('position', '<div class="error">', '</div>'); ?>
                            </div>
                            </div> -->
                         <div class="row admin-filed-wrap">
                            <div class="col-sm-4">
                               <label class="admin-label">Title</label> 
                            </div>
                            <div class="col-xs-6">
                              <div class="form-field-wrap">
                                  <input value="<?php if(isset($tempdata['title']) && !empty($tempdata['title'])){ echo $tempdata['title'];}elseif(isset($editdata->title) && !empty($editdata->title)){ echo $editdata->title;}else{ echo '';} ?>" name="title" type="text">
                              </div>
                                <?php echo form_error('title', '<div class="error">', '</div>'); ?>
                            </div>
                         </div>

                        </div>
                     </div>


                     <div class="row">
                        <div class="col-xs-12">
                            <textarea class="form-control" name="description" id="pg_content" style="height: 500px;">
                             <?php if(isset($tempdata['description']) && !empty($tempdata['description'])){ echo $tempdata['description'];}elseif(isset($editdata->description) && !empty($editdata->description)){ echo $editdata->description;}else{ echo '';} ?>
                            </textarea>
                                
                        </div>
                         <?php echo form_error('description', '<div class="error">', '</div>'); ?>

                     </div>


                     <div class="button-wrap text-center">
                        <input  type="reset" onclick='return window.location.href="<?php echo base_url() ?>admin/freetips"' value="Cancel" name="" class="commn-btn cancel">
                        <?php if(isset($_GET['id']) && !empty(trim($this->input->get('id'))) ): ?>
                            <input type="submit" value="Update" name="" class="commn-btn save">
                        <?php else: ?>
                         <input type="submit" value="Create" name="" class="commn-btn save">
                     <?php endif; ?>
                     </div>

                  </div>
               </div>
        <?php echo form_close(); ?>
               <!--Account-details wrapper close-->


               
              
            </div>
         </div>
      </div>
      <!--data  Wrap close-->
      