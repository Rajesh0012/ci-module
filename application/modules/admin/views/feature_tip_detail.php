  <div class="inner-right-panel">
               <!--breadcrumb wrap-->  
               <div class="breadcrumb-wrap">
                  <ul>
                     <li><a href="javascript:void(0)">Feature Tip </a></li>
                     <li><a href="javascript:void(0)">View Details</a></li>
                  </ul>
               </div>
               <!--breadcrumb wrap close-->  
               <!--Account-details wrapper-->   
               <div class="white-wrapper">
                  <span class="top-sticker">View Details</span>
                  <div class="user-view-action">
                     <div class="top-opt-wrap text-right">
                        <ul>
                           <li><button onclick="return window.location.href='<?php echo SITE_URL ?>/admin/freetips?per_page=<?php echo $this->input->get('per_page'); ?>'" class="commn-btn add-bttn">Back</button></li>
                           <li><button onclick="return window.location.href='<?php echo SITE_URL ?>/admin/Tips/edit_feature_tips?id=<?php echo base64_encode($tip_id); ?>&per_page=<?php echo $this->input->get('per_page'); ?>'" class="commn-btn add-bttn"><i class="fa fa-pencil" title="Edit" aria-hidden="true"></i> Edit</button></li>
                           <li><button onclick="setTip('<?php echo base64_encode($tip_id); ?>')" data-toggle="modal" data-target="#myModal-delete" class="commn-btn add-bttn"><i class="fa fa-trash" title="Delete" aria-hidden="true"></i> Delete</button></li>
                        </ul>
                     </div>
                  </div>
                  <div class="wrap">
                     <div class="row">
                        <div class="col-sm-4">
                           <figure class="usr-dtl-pic">
                              <img src="<?php echo !empty($tip_detail['image'])?$tip_detail['image']:''; ?>">
                           </figure>
                        </div>
                        <div class="col-sm-8">
                           <div class="row admin-filed-wrap">
                              <div class="col-xs-12">
                                 <h2 class="top-heading tip-any-match"><?php if(!empty($competitor_name)){ echo $competitor_name; } ?></h2>
                              </div>
                           </div>
                           <div class="row admin-filed-wrap">
                              <div class="col-xs-4">
                                 <label class="admin-label">Sport name</label> 
                              </div>
                              <div class="col-xs-8">
                                 <span class="show-label">Football</span>
                              </div>
                           </div>
                           <div class="row admin-filed-wrap">
                              <div class="col-xs-4">
                                 <label class="admin-label">Title</label> 
                              </div>
                              <div class="col-xs-8">
                                 <span class="show-label"><?php echo !empty($tip_detail['title'])?$tip_detail['title']:''; ?></span>
                              </div>
                           </div>
                           <div class="row admin-filed-wrap">
                              <div class="col-xs-4">
                                 <label class="admin-label">Tips Added By</label> 
                              </div>
                              <div class="col-xs-8">
                                 <span class="show-label"><?php echo !empty($tip_detail['create_date'])?date('d-m-Y',strtotime($tip_detail['create_date'])):''; ?>/span>
                              </div>
                           </div>
                           <div class="row admin-filed-wrap">
                              <div class="col-xs-4">
                                 <label class="admin-label">Status</label> 
                              </div>
                              <div class="col-xs-8">
                                 <span class="show-label"><?php if(!empty($tip_detail['status'] && $tip_detail['status']=='1')){ echo "Active";}else if(!empty($tip_detail['status'] && $tip_detail['status']=='2')){ echo "InActive";} ?></span>
                              </div>
                           </div>
                           <div class="row admin-filed-wrap">
                              <div class="col-xs-4">
                                 <label class="admin-label">Type</label> 
                              </div>
                              <div class="col-xs-8">
                                 <span class="show-label"><?php if(!empty($tip_detail['type'] && $tip_detail['type']=='1')){ echo "Game";}else if(!empty($tip_detail['type'] && $tip_detail['type']=='2')){ echo "Feature";} ?></span>
                              </div>
                           </div>
                        </div>
                     </div>
                      <input type="hidden" name="del_tip_id" id="del_tip_id">
                     <div class="row">
                        <div class="col-sm-4">
                           <div class="row admin-filed-wrap">
                              <div class="col-xs-4">
                                 <label class="admin-label">Game Time</label> 
                              </div>
                              <div class="col-xs-8">
                                 <span class="show-label">Saturday 12:30, Sky Sports</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-4">
                           <div class="row admin-filed-wrap">
                              <div class="col-xs-4">
                                 <label class="admin-label">Away Team</label> 
                              </div>
                              <div class="col-xs-8">
                                 <span class="show-label"><?php echo !empty($comptitior_detail['away'])?$comptitior_detail['away']:''; ?></span>
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-4">
                           <div class="row admin-filed-wrap">
                              <div class="col-xs-6">
                                 <label class="admin-label">Home Team</label> 
                              </div>
                              <div class="col-xs-6">
                                 <span class="show-label"><?php echo !empty($comptitior_detail['home'])?$comptitior_detail['home']:''; ?></span>
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
                           <?php echo !empty($tip_detail['description'])?$tip_detail['description']:''; ?>
                           </span>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-xs-12">
                           <h2 class="top-heading tip-any-match"><?php if(!empty($competitor_name)){ echo $competitor_name; } ?></h2>
                           <div class="tips-selection-wrap">
                              <ul>
                                  <?php 
                                           if(!empty($data_arr))
                                           {
                                              
                                      
                                      foreach($data_arr as $key=>$data)
                                      {
                                         $result=$Admin_model->fetch_data('tbl_tips_odds','odd_desc,position',array('where'=>array('tip_id'=>$tip_id,'match_id'=>$match_id,'odd'=>trim($key))),true);
                                  ?>
                                  <li>
                                    <span class="heading"><?php echo !empty($data)?trim($data):'N/A'; ?></span>
                                    <span class="fraction-digit"><?php echo !empty($key)?trim($key):'N/A'; ?></span>
                                    <p>
                                       <?php echo !empty($result['odd_desc'])?trim($result['odd_desc']):'N/A'; ?>
                                    </p>
                                 </li>
                                  <?php
                                          
                                      }
                                  } ?>
                                 
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
      
      <!-- Modal -->
<div id="myModal-delete" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-alt-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title modal-heading">Delete </h4>
            </div>
            
            <div class="modal-body">
                <p class="modal-para">Are you sure want to delete this tip?</p>
                <div class="button-wrap">
                    <input data-dismiss="modal" type="button" value="Cancel" class="commn-btn cancel" name="">
                    <input type="submit" value="delete" class="commn-btn save" onclick="deleteFeatureTip();" name="decider">
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--delete Modal Close-->
<script>
    function deleteFeatureTip(){
            
            var dtip_id=$('#del_tip_id').val();
            
            
              $.ajax({

            type:'POST',
            url: siteurl + '/admin/Tips/delete_feature_tip',
            data:{did:dtip_id,'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'},

            success:function (data) {
               
               
                if(data=='success')
                {
                    alert('Tip deleted successfully.');
                    window.location.href=siteurl+'/admin/freetips';
                }


               // $('#eventeventhere').html(data);
            },
            error:function (jqXHR,exception) {

                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'URL page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }

                alert(msg);
            }


        });
            
        }
</script>
      