
<!--Header close-->
<div class="inner-right-panel">
    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ul>
            <li><a href="javascript:void(0)">User Management</a></li>
        </ul>
    </div>

    <!--breadcrumb wrap close-->
    <!--Filter Section -->
    <?php $get=site_url().'?'.$this->input->server('QUERY_STRING'); $attribute= ['action'=>$get,'id'=>"searchform", 'method'=>"get"]; echo form_open('',$attribute); ?>
    <div class="fltr-srch-wrap clearfix">
        <div class="left-lg-col">
            <div class="row">
                <div class="col-lg-8">
                    <div class="srch-wrap">
                        <span onclick="return window.location.href='<?php echo base_url(); ?>admin/users/user_list'"  class="srch-close-icon <?php if(isset($search['searchname']) && !empty(trim($search['searchname']))){ echo 'show-srch'; } ?>"></span>
                        <span onclick="return $('#searchform').submit()" class="search-icon"></span>
                        <input type="text" value="<?php if(isset($search['searchname']) && !empty(trim($search['searchname']))){ echo $search['searchname']; } ?>" name="searchname" placeholder="Search Email, Full Name Or Mobile No." class="srch-box">

                    </div>
                </div>
            </div>
        </div>
        <div class="right-lg-col">
            <div class="top-opt-wrap text-right">
                <ul>
                    <li><a href="javascript:void(0)" title="Generate Excel Export"><img src="<?php echo BASE_URL; ?>/admin/images/export-file.svg"> </a></li>
                    <li><a href="javascript:void(0)" title="Filter" id="filter-side-wrapper"><img src="<?php echo BASE_URL; ?>/admin/images/filter.svg"></a></li>
                    <li><button type="button" onclick="return window.location.href='<?php echo base_url(); ?>admin/users/add_user'"  class="commn-btn add-bttn" title="Add Brand">Add New User</button></li>
                    <li><button type="button" class="commn-btn add-bttn" title="User Report" data-toggle="modal" data-target="#myModal-userreport"> User Report</button></li>
                </ul>
            </div>
        </div>
    </div>
    <!--Filter Section Close-->
    <!--Filter Wrapper-->


    <div class="filter-wrap">
        <h2 class="fltr-heading">Filter </h2>
        <div class="inner-filter-wrap">
            <div class="fltr-field-wrap">
                <label class="admin-label">Registered Via</label>
                <div class="commn-select-wrap">
                    <select name="registerd_via" class="selectpicker">
                        <option selected value="">--select--</option>
                        <option <?php if(isset($search['registerd_via']) && $search['registerd_via'] == REGISTER_VIA_NORMAL){ echo 'selected';} ?> value="<?php echo REGISTER_VIA_NORMAL; ?>">Normal</option>
                        <option <?php if(isset($search['registerd_via']) && $search['registerd_via'] == REGISTER_VIA_FACEBOOK){ echo 'selected';} ?> value="<?php echo REGISTER_VIA_FACEBOOK; ?>">Facebook</option>
                        <option <?php if(isset($search['registerd_via']) && $search['registerd_via'] == REGISTER_VIA_TWITTER){ echo 'selected';} ?> value="<?php echo REGISTER_VIA_TWITTER; ?>">Twitter</option>
                        <option <?php if(isset($search['registerd_via']) && $search['registerd_via'] == REGISTER_VIA_GOOGLE){ echo 'selected';} ?> value="<?php echo REGISTER_VIA_GOOGLE; ?>">Google</option>
                        <option <?php if(isset($search['registerd_via']) && $search['registerd_via'] == REGISTER_VIA_ADMIN){ echo 'selected';} ?> value="<?php echo REGISTER_VIA_ADMIN; ?>">Admin</option>
                    </select>
                </div>
            </div>
                <div class="row">
                <div class="col-xs-6">
                    <div class="fltr-field-wrap">
                        <label class="admin-label">From</label>
                        <div class="input-group date" id="datetimepicker1">
                            <input type="text" value="<?php if(isset($search['from_date']) && !empty($search['from_date'])){ echo $search['from_date'];} ?>" id="datetime1" name="from_date" placeholder="From">
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
                            <input value="<?php if(isset($search['to_date']) && !empty($search['to_date'])){ echo $search['to_date'];} ?>" name="to_date" type="text" id="datetime1" placeholder="From">
                            <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                           </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="fltr-field-wrap">
                        <label class="admin-label">Device Type</label>
                        <div class="commn-select-wrap">
                            <select name="device_type" class="selectpicker">
                                <option selected value="">--select--</option>
                                <option <?php if(in_array(DEVICE_TYPE_ANDROID,$search)){ echo 'selected';} ?> value="<?php echo DEVICE_TYPE_ANDROID; ?>">Android</option>
                                <option <?php if(in_array(DEVICE_TYPE_I_PHONE,$search)){ echo 'selected';} ?> value="<?php echo DEVICE_TYPE_I_PHONE; ?>">I-Phone</option>
                                <option <?php if(in_array(DEVICE_TYPE_WEB,$search)){ echo 'selected';} ?> value="<?php echo DEVICE_TYPE_WEB; ?>">Web</option>
                                </select>
                        </div>
                    </div>
                </div>
                <!--<div class="col-xs-6">
                    <div class="fltr-field-wrap">
                        <label class="admin-label">App Version</label>
                        <div class="commn-select-wrap">
                            <select class="selectpicker">
                                <option>V.01</option>
                                <option>V.02</option>
                                <option>V.03</option>
                            </select>
                        </div>
                    </div>
                </div>-->
            </div>

            <div class="button-wrap text-center">
                <input type="reset" onclick="resetsearch()" value="Reset" name="" class="commn-btn cancel">
                <input type="Submit" value="FILTER" name="" class="commn-btn save">

            </div>
        </div>
    </div>

    <!--Filter Wrapper Close-->
    <div class="display-wrap clearfix">
        <div class="left-column">
            <div class="display">
                <select onchange="return $('#searchform').submit()" name="display" class="selectpicker">
                    <option <?php if(in_array(DISPLAY1,$search)){ echo 'selected';} ?> value="<?php echo DISPLAY1; ?>">Display 5</option>
                    <option <?php if(in_array(DISPLAY2,$search)){ echo 'selected';} ?> value="<?php echo DISPLAY2; ?>">Display 10</option>
                    <option <?php if(in_array(DISPLAY3,$search)){ echo 'selected';} ?> value="<?php echo DISPLAY3; ?>">Display 15</option>
                </select>

            </div>

        </div>
        <div class="left-column">
            <div class="display">
                <?php if($available_users>0) :?>
                    <span> <?php  echo ($page+1) ?> - <?php echo ($page) + (count($user['userlist'])).' of '. $available_users; ?></span>
                <?php endif; ?>
            <!--<span>Total Available Users</span>!-->
            <?php //echo $available_users; ?>

            </div>
        </div>
        <div class="right-column">
        </div>
        <div><?php if(!empty($this->session->flashdata('msg'))){echo $this->session->flashdata('msg');} ?></div>
    </div>
    <input type="hidden" id="sort" name="sort">
    <?php echo form_close(); ?>
    <!--Table-->
    <?php
    $attribute1=array(

            'id'=>'listingform',


    );
    echo form_open(site_url().'admin/users/blockAndDelete',$attribute1); ?>
<script>
    function sort(str) {

        $('#sort').attr('value',str);
        $('#searchform').submit()
    }
</script>

    <div class="white-wrapper">

        <div class="table-responsive clearfix">
            <!--table div-->
            <table id="myTable" class="list-table table " cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th><div class="th-checkbox">
                            <input  class="filter-type filled-in" type="checkbox" name="filter" id="flowers" value="flowers">
                            <label for="flowers" class="lbl-check"><span></span></label>
                        </div> <i style="display: none" id="blok" class="fa fa-ban" title="Block" aria-hidden="true" data-toggle="modal" data-target="#myModal-block"></i>
                        <i id="showtrash" style="display: none" class="fa fa-trash" title="Delete" aria-hidden="true" data-toggle="modal" data-target="#myModal-trashuser"></i></th>
                    <th>S.No.</th>
                    <th>Register Date

                        <?php if(isset($_GET['sort']) && $this->input->get('sort') == 'datedesc'): ?>
                            <i onclick="sort('dateasc')" class="fa fa-sort-asc" aria-hidden="true"></i>
                        <?php elseif(isset($_GET['sort']) && $this->input->get('sort') == 'dateasc'): ?>

                            <i onclick="sort('datedesc')" class="fa fa-sort-desc" aria-hidden="true"></i>
                        <?php else: ?>
                            <i onclick="sort('dateasc')" class="fa fa-sort-asc" aria-hidden="true"></i>
                            <i onclick="sort('datedesc')" class="fa fa-sort-desc" aria-hidden="true"></i>

                        <?php endif; ?>

                    </th>
                    <th>Register Via</th>
                    <th>Full Name
                        <?php if(isset($_GET['sort']) && $this->input->get('sort') == 'namedesc'): ?>
                        <i id="nameasc" onclick="sort('nameasc')" class="fa fa-sort-asc" aria-hidden="true"></i>
                        <?php elseif(isset($_GET['sort']) && $this->input->get('sort') == 'nameasc'): ?>

                            <i onclick="sort('namedesc')" class="fa fa-sort-desc" aria-hidden="true"></i>
                        <?php else: ?>
                            <i id="nameasc" onclick="sort('nameasc')" class="fa fa-sort-asc" aria-hidden="true"></i>
                            <i onclick="sort('namedesc')" class="fa fa-sort-desc" aria-hidden="true"></i>

                        <?php endif; ?>
                    </th>
                    <th>Email Address </th>
                    <th>Phone Number </th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <?php  $no=$page; if(!empty($user['userlist'])):   $chk=2; foreach ($user['userlist'] as $key=>$users): ?>
                    <tr class="text-center item">
                        <td>
                            <div class="th-checkbox">
                                <input class="filter-type filled-in checked" type="checkbox" name="delete[]" id="flowers<?php echo $chk; ?>" value="<?php echo $users->id; ?>">
                                <label for="flowers<?php echo $chk++; ?>" class="lbl-check"><span></span></label>
                            </div>
                        </td>
                        <td><?php echo  ++$no; $this->session->set_flashdata('page',$no); ?></td>
                        <td><?php echo $users->create_date; ?></td>
                        <td><?php if($users->signup_type == 1){  echo 'Web';}elseif ($users->signup_type == 2){ echo 'Facebook';}elseif ($users->signup_type == 3){ echo 'Google';}elseif ($users->signup_type == 4){ echo 'Twitter';}elseif ($users->signup_type == 5){ echo 'Guest';}else{ echo 'Admin';} ?></td>
                        <td><a href="javascript:void(0)"><?php echo $users->full_name; ?></a></td>
                        <td><?php echo $users->email; ?></td>
                        <td><?php echo !empty($users->phone_number)?$users->phone_number:'-'; ?></td>
                        <td class="text-center">
                        <a href="<?php echo site_url().'admin/users/add_user?userId='.$users->id.'&per_page='?><?php echo isset($_GET['per_page']) && !empty($this->input->get('per_page'))?$this->input->get('per_page'):'';
                        if(isset($searchname) && !empty($searchname)) { echo $searchname; }
                        if(isset($to_date) && !empty($to_date)) { echo $to_date; }
                        if(isset($device_type) && !empty($device_type)) { echo $device_type; }
                        if(isset($registerd_via) && !empty($registerd_via)) { echo $registerd_via; }
                        if(isset($sdisplay) && !empty($sdisplay)) { echo $sdisplay; }
                        if(isset($sort) && !empty($sort)) { echo $sort; }
                        if(isset($from_date) && !empty($from_date)) { echo $from_date; }
                        if(isset($display) && !empty($display)) { echo $display; }
                       ?>"> <i class="fa fa-pencil" title="Edit" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                <?php  endforeach; ?>
                    <?php  else: ?>
                    <tr>
                    <td align="center" colspan="7">No Result Found!</td>
                    </tr>
                <?php  endif; ?>
                </tbody>
            </table>
        </div>
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
</div>
</div>
</div>
<!--data  Wrap close-->
<!--Delete  Modal Close-->
<!-- Modal -->
<!-- Modal -->
<div id="myModal-trashuser" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-alt-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title modal-heading">Delete </h4>
            </div>
            <div class="modal-body">
                <p class="modal-para">Are you sure want to delete this user?</p>
                <div class="button-wrap">
                    <input data-dismiss="modal" type="button" value="Cancel" class="commn-btn cancel" name="">
                    <input type="submit" value="delete" class="commn-btn save" name="decider">
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
                    <input data-dismiss="modal" type="button" value="Cancel" class="commn-btn cancel" name="">
                    <input type="submit" value="block" class="commn-btn save" name="decider">
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--block Modal Close-->
<?php echo form_open(); ?>





<!-- Modal -->
<div id="myModal-userreport" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-alt-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title modal-heading">Download Registerd User Reports </h4>
            </div>
            <div class="modal-body">
                <label class="admin-label">Select Report Type:</label>

                <div class="button-wrap">
                    <ul>
                        <li>
                            <div class="radio-us">
                                <input class="filter-type filled-in" type="radio" name="filter" id="flowers" value="flowers">
                                <label for="flowers" class="radio-checkin"><span></span>Remember me</label>
                            </div>
                        </li>
                        <li>
                            <div class="radio-us">
                                <input class="filter-type filled-in" type="radio" name="filter" id="flowers" value="flowers">
                                <label for="flowers" class="radio-checkin"><span></span>Weekly</label>
                            </div>
                        </li>

                        <li>
                            <div class="radio-us">
                                <input class="filter-type filled-in" type="radio" name="filter" id="flowers" value="flowers">
                                <label for="flowers" class="radio-checkin"><span></span>Monthly</label>
                            </div>
                        </li>
                        <li>
                            <div class="radio-us">
                                <input class="filter-type filled-in" type="radio" name="filter" id="flowers" value="flowers">
                                <label for="flowers" class="radio-checkin"><span></span>Yearly</label>
                            </div>
                        </li>
                    </ul>
                </div>


                <label class="admin-label">Select Date Range</label>
                <div class="button-wrap">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="input-group date" id="datetimepicker3">
                                <input type="text" id="datetimepicker3" value="" placeholder="From">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="input-group date" id="datetimepicker4">
                                <input type="text" id="datetimepicker4" value="" placeholder="To">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                            </div>
                        </div>
                    </div>


                </div>


                <div class="button-wrap text-center">
                    <!-- <input type="button" value="Cancel" class="commn-btn cancel" name=""> -->
                    <input type="button" value="Download Excel Report" class="commn-btn" name="">
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!--block Modal Close-->
