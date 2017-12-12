<style>
    .error{
        color: red;
    }
</style>

    <!--Header close-->
    <div class="inner-right-panel">
        <!--breadcrumb wrap-->
        <div class="breadcrumb-wrap">
            <ul>
                <li><a href="<?php echo base_url() ?>/admin/users/user_list">User </a></li>
                <li><a href="javascript:void(0)">View Details</a></li>
            </ul>
        </div>
        <!--breadcrumb wrap close-->
        <!--Account-details wrapper-->
        <?php

        $searchname = isset($searchname) && !empty($searchname)? $searchname:'';
        $per_page = isset($_GET['per_page']) && !empty($_GET['per_page'])? $_GET['per_page']:'';
        $to_date = isset($to_date) && !empty($to_date)? $to_date:'';
        $device_type = isset($device_type) && !empty($device_type)? $device_type:'';
        $registerd_via = isset($registerd_via) && !empty($registerd_via)? $registerd_via:'';
        $sdisplay = isset($sdisplay) && !empty($sdisplay)? $sdisplay:'';
        $sort = isset($sort) && !empty($sort)? $sort:'';
        $from_date = isset($from_date) && !empty($from_date)? $from_date:'';
       echo form_open_multipart('admin/users/add_user?userId='.$userId.$per_page.$from_date.$sort.$sdisplay.$registerd_via.$searchname.$to_date.$device_type);
        ?>
        <div class="white-wrapper">
            <span class="top-sticker">Add Details</span>
            <div class="wrap">
                <div class="row">
                    <div class="col-sm-4">
                        <figure class="usr-dtl-pic">

                             <?php  if(!empty($this->input->get('userId',TRUE))): ?>
                            <div class="post_gallery">
                                 <img height="160px"  id="placeholder" src="<?php if(isset($temp->image) && !empty($temp->image)){ echo $temp->image; } else { echo base_url().'public/admin/images/no_image.jpeg'; } ?>">
                             </div>
                             <?php else: ?>
                            <div class="post_gallery">
                                 <img id="placeholder" src="<?php echo base_url(); ?>public/admin/images/placeholder-user.png">
                            </div>
                             <?php endif;?>
                            
                            <label class="camera" for="upload-img"><i class="fa fa-camera" aria-hidden="true"></i></label>

                            <input type="file" accept="image/png,image/jpg,image/jpeg" name="pimage" id="upload-img" style="display:none;">
                        </figure>
                    </div>
                    <div class="col-sm-6">
                        <span class="error">
                        <?php  if(isset($msg) && !empty($msg)){ echo $msg;} ?>
                        <?php if(isset($exception) && !empty($exception)){ echo $exception;} ?>
                       </span>
                        <div class="row admin-filed-wrap">
                            <div class="col-xs-4">
                                <label class="admin-label">Full Name<mark>*</mark></label>
                            </div>
                            <div class="col-xs-8">
                                <div class="field-wrap">
                                    <input value="<?php if(isset($tempdata) && !empty($tempdata)){ echo $tempdata['full_name'];}elseif(isset($temp->full_name) && !empty($temp->full_name)){ echo $temp->full_name;}else{ echo '';} ?>" name="full_name" type="text" >
                                    <?php echo form_error('full_name', '<div class="error">', '</div>'); ?>

                                </div>
                            </div>
                        </div>
                        <div class="row admin-filed-wrap">
                            <div class="col-xs-4">
                                <label class="admin-label">Email Address<mark>*</mark> </label>
                            </div>
                            <div class="col-xs-8">
                                <input value="<?php if(isset($tempdata) && !empty($tempdata)){ echo $tempdata['email'];}elseif(isset($temp->email) && !empty($temp->email)){ echo $temp->email;}else{ echo '';} ?>" name="email" type="text" >
                                <?php echo form_error('email', '<div class="error">', '</div>'); ?>
                            </div>
                        </div>
                        <div class="row admin-filed-wrap">
                            <div class="col-xs-4">
                                <label class="admin-label">Phone Number<mark>*</mark> </label>
                            </div>
                            <div class="col-xs-8">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="mob-display">
                                            <select name="country_code"  class="selectpicker">
                                                <option value="">-select code--</option>
                                                <?php if(isset($country_code) && !empty($country_code)): foreach ($country_code as $ckey=>$cvalues): ?>

                                                    <option  <?php if(isset($tempdata['country_code']) && in_array($tempdata['country_code'],(array) $cvalues)){ echo 'selected';}elseif (isset($temp->country_code) && !empty($temp) && $temp->country_code == $cvalues->country_iso_code){ echo 'selected';}else{ echo '';} ?>><?php echo $cvalues->country_iso_code; ?></option>
                                               <?php endforeach;
                                               else:?>
                                               <option>No Results found</option>
                                               <?php endif;  ?>
                                                </select>
                                            <?php echo form_error('country_code', '<div class="error">', '</div>'); ?>
                                        </div>
                                    </div>
                                    <div class="col-xs-8">
                                        <div class="field-wrap">
                                            <input value="<?php if(isset($tempdata) && !empty($tempdata)){ echo $tempdata['phone_number'];}elseif(isset($temp->phone_number) && !empty($temp->phone_number)){ echo $temp->phone_number;}else{ echo '';} ?>" name="phone_number" type="text" >
                                            <?php echo form_error('phone_number', '<div class="error">', '</div>'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if(empty($userId)): ?>
                        <div class="row admin-filed-wrap">
                            <div class="col-xs-4">
                                <label class="admin-label">Password<mark>*</mark> </label>
                            </div>
                            <div class="col-xs-8">
                                <div class="field-wrap">
                                    <input value="<?php if(isset($tempdata) && !empty($tempdata)){ echo $tempdata['password'];}elseif(isset($temp->password) && !empty($temp->password)){ echo $temp->password;}else{ echo '';} ?>"     name="password" type="password" >
                                    <?php echo form_error('password', '<div class="error">', '</div>'); ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="button-wrap text-center">
                            <input onclick="return window.location.href='<?php echo site_url(); ?>admin/users/user_list?per_page=<?php
                            if(isset($_GET['per_page']) && !empty($this->input->get('per_page'))){ echo $this->input->get('per_page');}
                            if(isset($from_date) && !empty($from_date)) { echo $from_date; }
                            if(isset($searchname) && !empty($searchname)) { echo $searchname; }
                            if(isset($to_date) && !empty($to_date)) { echo $to_date; }
                            if(isset($device_type) && !empty($device_type)) { echo $device_type; }
                            if(isset($registerd_via) && !empty($registerd_via)) { echo $registerd_via; }
                            if(isset($sdisplay) && !empty($sdisplay)) { echo $sdisplay; }
                            if(isset($sort) && !empty($sort)) { echo $sort; }
                            if(isset($display) && !empty($display)) { echo $display; }
                             ?>'" type="button" value="Cancel" name="" class="commn-btn cancel">
                            <?php if(isset($userId) && !empty($userId)): ?>
                             <input type="Submit" value="Update" name="" class="commn-btn save">
                            <?php else: ?>
                                <input type="Submit" value="Add" name="" class="commn-btn save">
                            <?php endif; ?>
                           </div>

                    </div>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
        <!--Account-details wrapper close-->
    </div>

