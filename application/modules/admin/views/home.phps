
        <!--Header close-->
        <div class="inner-right-panel">
            <!--breadcrumb wrap-->
            <div class="breadcrumb-wrap">
                <ul>
                    <li><a href="#">Home Managment</a></li>
                </ul>
            </div>
            <!--breadcrumb wrap close-->
            <!--Filter Section -->
            <?php
            $attribute=array(
                'method'=>'get',
                'id'=>'oddsform'

            );

            echo form_open('',$attribute); ?>
            <div class="fltr-srch-wrap clearfix">
                <div class="left-lg-col">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="srch-wrap">
                                <span onclick="return window.location.href='<?php echo base_url(); ?>admin/Home/homePage'"  class="srch-close-icon <?php if(isset($search['searchkeywords']) && !empty(trim($search['searchkeywords']))){ echo 'show-srch'; } ?>"></span>
                                <span onclick="return $('#oddsform').submit()" class="search-icon"></span>
                                <input  type="text" value="<?php if(isset($search['searchkeywords']) && !empty(trim($search['searchkeywords']))){ echo $search['searchkeywords']; } ?>" name="searchkeywords" placeholder="Search Event Name Or Comptitor Name ..." class="srch-box">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="right-lg-col">
                    <div class="top-opt-wrap text-right">

                        <ul>

                            <li><a href="javascript:void(0)" title="Filter" id="filter-side-wrapper"><img src="<?php echo BASE_URL; ?>/admin/images/filter.svg"></a></li>
                            <li><button type="submit" class="commn-btn add-bttn" title="Remove">Remove</button></li>
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
                        <label class="admin-label">Select Game Category</label>
                        <div class="commn-select-wrap">
                            <select name="gamecategory" class="selectpicker">
                                <option value="">All</option>
                                <?php if(isset($categories) && !empty($categories)): foreach ($categories as $key=>$values): ?>
                                    <option <?php if(isset($search['gamecategory']) && !empty($search['gamecategory']) && $search['gamecategory'] == $values->category_id) { echo 'selected';}?> value="<?php echo $values->category_id; ?>"><?php echo $values->category_name; ?></option>
                                <?php endforeach; else:
                                    ?>
                                    <option>No Results</option>
                                    <?php
                                endif; ?>

                            </select>
                        </div>
                    </div>


                    <div class="button-wrap text-center">
                        <input type="button" onclick="return window.location.href=siteurl+'/admin/Home/homePage'" value="Reset" name="" class="commn-btn cancel">
                        <input type="submit" value="Filter" name="" class="commn-btn save">
                    </div>
                </div>
            </div>
            <!--Filter Wrapper Close-->
            <div class="display-wrap clearfix">
                <div class="left-column">
                    <div class="display">
                        <select onchange="return $('#oddsform').submit()" name="display" class="selectpicker">
                            <option <?php if(!empty($search) && in_array(DISPLAY1,$search)){ echo 'selected';} ?> value="<?php echo DISPLAY1; ?>">Display 5</option>
                            <option <?php if(!empty($search) && in_array(DISPLAY2,$search)){ echo 'selected';} ?> value="<?php echo DISPLAY2; ?>">Display 10</option>
                            <option <?php if(!empty($search) && in_array(DISPLAY3,$search)){ echo 'selected';} ?> value="<?php echo DISPLAY3; ?>">Display 15</option>
                        </select>
                    </div>
                </div>
                <span>Results Found: <?php echo $available_odds; ?></span>
                <span><?php if(isset($msg) && !empty($msg)) { echo $msg; } ?></span>
                <span><?php if($this->session->flashdata('msg')) { echo $this->session->flashdata('msg');} ?></span>
                <div class="right-column">
                </div>
            </div>
            <!--Table-->
            <div class="white-wrapper">
                <div class="table-responsive clearfix">
                    <!--table div-->
                    <table id="example" class="list-table table " cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th><div class="th-checkbox">
                              <input class="filter-type filled-in" type="checkbox" name="filter" id="flowers" value="flowers">
                               <label for="flowers" class="lbl-check"><span></span></label>
                             </div> Select </th>
                            <th>S.No.</th>
                            <th>Game Category<i class="fa fa-sort" aria-hidden="true"></i></th>
                            <th>Event Name</th>
                            <th>
                                Betting <i class="fa fa-sort" aria-hidden="true"></i>
                                <!-- <i class="fa fa-sort-amount-desc" aria-hidden="true"></i> -->
                            </th>
                            <th>Best Odds </th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php  $i=$page; $chk=2; if(isset($odds) && !empty($odds)): foreach ($odds as $key=>$values): ?>
                        <tr class="text-center">
                            <td>
                                <div class="th-checkbox">
                                    <input class="filter-type filled-in checked" type="checkbox" name="delete[]" id="flowers<?php echo $chk; ?>" value="<?php echo $values->id; ?>">
                                    <label for="flowers<?php echo $chk++; ?>" class="lbl-check"><span></span></label>
                                </div>
                            </td>
                            <td><?php echo ++$i; ?></td>
                            <td><?php if(isset($values->category_name) && !empty($values->category_name)){ echo $values->category_name; }else{echo 'data not availabe';}  ?></td>
                            <td><?php  if(isset($values->season_name) && !empty($values->season_name)){ echo $values->season_name; }else{echo 'data not availabe';}  ?></td>
                            <td><?php  if(isset($values->competitor_name) && !empty($values->competitor_name)){ echo $values->competitor_name;}else{echo 'data not availabe';}  ?></td>
                            <td><?php  if(isset($values->odds) && !empty($values->odds)){ echo $values->odds; }else{echo 'data not availabe';}  ?></td>
                        </tr>
                    <?php endforeach;
                    else:?>
                    <tr><td colspan="6">No Result Found!</td></tr>
                    <?php endif; ?>
                         </tbody>
                    </table>
                </div>
            </div>
            <!--Table listing-->
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

            <div class="fltr-srch-wrap clearfix">

              <!--  <div class="clearfix">
                    <div class="left-lg-col">
                        <div class="top-opt-wrap">
                            <ul>
                                <li><button class="commn-btn add-bttn" title="Remove">Add Bet For Home</button></li>
                            </ul>
                        </div>
                    </div>
                    <div class="right-lg-col">

                    </div>
                </div>-->


                <div class="row">
                    <div class="col-sm-4">
                        <label class="admin-label">Select Sports</label>
                        <div class="commn-select-wrap">
                            <select class="selectpicker" onchange="getEventDetails(this.value)">
                                <option value="all">All</option>

                                <?php if(isset($sports) && !empty($sports)): foreach($sports as $sport): ?>

                                <option value="<?php echo $sport['sport_id']; ?>"><?php echo $sport['sport_name']; ?></option>

                                <?php endforeach;endif; ?>

                            </select>
                        </div>
                    </div>
                          <input type="hidden" id="set_link" value="">
                    <div class="col-sm-4">
                        <label class="admin-label">Events Name</label>
                        <div id="sporteventhere" class="commn-select-wrap">
                            <select  class="selectpicker">
                                <option>no event</option>
                            </select>
                        </div>
                    </div>
                    <div class="top-opt-wrap">
                        <ul>

                     <li><button type="button" onclick="get_details()" class="commn-btn add-bttn" title="Details">get Details</button></li>

                        </ul>
                    </div>
                </div>
             </div>

        <?php echo form_close(); ?>
<!--data  Wrap close-->


