
<!--Header close-->
<div class="inner-right-panel">
    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ul>
            <li><a href="#">Free Tips </a></li>
            <li><a href="#">View Details</a></li>
        </ul>
    </div>
    <!--breadcrumb wrap close-->
    <!--Account-details wrapper-->
    <div class="white-wrapper">
        <span class="top-sticker">View Details</span>
        <div class="user-view-action">
            <div class="top-opt-wrap text-right">
                <ul>
                    <li><button onclick="return window.location.href='<?php echo base_url() ?>admin/freetips?per_page=<?php echo $this->input->get('per_page'); ?>'" class="commn-btn add-bttn">Back</button></li>
                    <li><button onclick="return window.location.href='<?php echo base_url() ?>admin/games?id=<?php echo base64_encode($details->id); ?>'" class="commn-btn add-bttn"><i class="fa fa-pencil" title="Edit" aria-hidden="true"></i> Edit</button></li>
                    <li><button  data-toggle="modal" data-target="#tipsModal-delete" class="commn-btn add-bttn" ><i class="fa fa-trash" title="Delete" aria-hidden="true"></i> Delete</button></li>

                </ul>
            </div>
        </div>
        <div class="wrap">
            <div class="row">
                <div class="col-sm-4">
                    <figure class="usr-dtl-pic">
                        <img src="<?php if(isset($details->image) && !empty($details->image)) { echo $details->image;}else{ echo base_url().'public/admin/images/no_image.jpeg';} ?>">
                    </figure>
                </div>
                <div class="col-sm-8">
                    <!--<div class="row admin-filed-wrap">
                        <div class="col-xs-12">
                            <h2 class="top-heading tip-any-match">Liverpool V Man UTD -Saturday 12:30,Sky</h2>
                        </div>
                    </div>-->
                    <div class="row admin-filed-wrap">
                        <div class="col-xs-4">
                            <label class="admin-label">Sports name</label>
                        </div>
                        <div class="col-xs-8">
                            <span class="show-label"><?php echo $details->sport_name; ?></span>
                        </div>
                    </div>
                    <div class="row admin-filed-wrap">
                        <div class="col-xs-4">
                            <label class="admin-label">Title</label>
                        </div>
                        <div class="col-xs-8">
                            <span class="show-label"><?php echo $details->title; ?></span>
                        </div>
                    </div>
                    <div class="row admin-filed-wrap">
                        <div class="col-xs-4">
                            <label class="admin-label">Tips Added By</label>
                        </div>
                        <div class="col-xs-8">
                            <span class="show-label"><?php echo $details->create_date; ?></span>
                        </div>
                    </div>
                    <div class="row admin-filed-wrap">
                        <div class="col-xs-4">
                            <label class="admin-label">Status</label>
                        </div>
                        <div class="col-xs-8">
                            <span class="show-label"><?php if(isset($details->status) && !empty($details->status)){ echo $details->status == TIPS_ACTIVE?'Active':'Inactive';}; ?></span>
                        </div>
                    </div>
                    <div class="row admin-filed-wrap">
                        <div class="col-xs-4">
                            <label class="admin-label">Type</label>
                        </div>
                        <div class="col-xs-8">
                            <span class="show-label"><?php if(isset($details->type) && !empty($details->type)){ echo  $details->type == 1?'Game':'Features';} ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row admin-filed-wrap">
                <div class="col-xs-4">
                    <label class="admin-label">Description</label>
                </div>
                <div class="col-xs-8">
                           <span class="show-label">
                         <?php echo $details->description; ?>
                           </span>
                </div>
            </div>
           <div id="tipsModal-delete" class="modal fade" role="dialog">
         <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header modal-alt-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title modal-heading">BLOCK </h4>
               </div>
               <div class="modal-body">
                  <p class="modal-para">Are you sure want to delete this tips?</p>
                  <div class="button-wrap">
                     <input type="button" data-dismiss="modal" value="Cancel" class="commn-btn cancel" name="">
                     <input type="button" onclick="return window.location.href='<?php echo SITE_URL; ?>/admin/tips/deleteRow?delete=<?php echo base64_encode($details->id); ?>';
" value="Delete" class="commn-btn save" name="">
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
    <!--Account-details wrapper close-->

</div>
</div>
</div>



