
        <!--Header close-->
        <div class="inner-right-panel">
            <!--breadcrumb wrap-->
            <div class="breadcrumb-wrap">
                <ul>
                    <li><a href="javascript:void(0)">Home Management</a></li>
                </ul>
            </div>
            <!--breadcrumb wrap close-->
            <!--Filter Section -->
            <?php

            $i=$page;
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
                                <button tabindex="1" onclick="return $('#oddsform').submit()" class="search-icon"></button>
                                <input type="text" value="<?php if(isset($search['searchkeywords']) && !empty(trim($search['searchkeywords']))){ echo $search['searchkeywords']; } ?>" name="searchkeywords" placeholder="Search Tournament Name Or Comptitor Name ..." class="srch-box">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="right-lg-col">
                    <div class="top-opt-wrap text-right">

                        <ul>

                            <li><a href="javascript:void(0)" title="Filter" id="filter-side-wrapper"><img src="<?php echo BASE_URL; ?>/admin/images/filter.svg"></a></li>
                            <li><button onclick="return false" id="removebttn" style="display: none;"  data-toggle="modal" data-target="#myModal-del" class="commn-btn add-bttn" title="Remove">Delete</button></li>
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
                        <label class="admin-label">Select Sport</label>
                        <div class="commn-select-wrap">
                            <select name="gamecategory" class="selectpicker">
                                <option value="">All</option>
                                <?php if(isset($sports) && !empty($sports)): foreach ($sports as $key=>$values): ?>
                                    <option <?php if(isset($search['gamecategory']) && !empty($search['gamecategory']) && $search['gamecategory'] == $values['sport_id']) { echo 'selected';}?> value="<?php echo $values['sport_id']; ?>"><?php echo $values['sport_name']; ?></option>
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
                        <input type="submit" id="filterss" value="Filter" class="commn-btn save">
                    </div>
                </div>
            </div>
            <script>
                function sort(str) {

                    $('#sort').attr('value',str);
                    $('#oddsform').submit()
                }
            </script>
            <!--Filter Wrapper Close-->
            <input type="hidden" id="sort" name="sort">
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

                <span><?php if(isset($msg) && !empty($msg)) { echo $msg; } ?></span>
                <span><?php if($this->session->flashdata('msg')) { echo $this->session->flashdata('msg');} ?></span>
                <div class="right-column">
                </div>
            </div>
            <!--Table-->
            <div class="white-wrapper">
                <?php if($available_odds>0) :?>
                <span> <?php $k=1; echo (++$page) ?> - <?php echo ($i)+ (count($odds)).' of '. $available_odds; ?></span>
                <?php endif; ?>
                <div class="table-responsive clearfix">
                    <!--table div-->
                    <table id="myTable" class="list-table table " cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th><div class="th-checkbox">
                              <input class="filter-type filled-in" type="checkbox" name="filter" id="flowers" value="flowers">
                               <label for="flowers" class="lbl-check"><span></span></label>
                             </div> </th>
                            <th>S.No.</th>
                            <th>Sport Name
                                <?php if(isset($_GET['sort']) && $this->input->get('sort') == 'sportdesc'): ?>
                                    <i onclick="sort('sportasc')" class="fa fa-sort-asc" aria-hidden="true"></i>
                                <?php elseif(isset($_GET['sort']) && $this->input->get('sort') == 'sportasc'): ?>

                                    <i onclick="sort('sportdesc')" class="fa fa-sort-desc" aria-hidden="true"></i>
                                <?php else: ?>
                                    <i onclick="sort('sportasc')" class="fa fa-sort-asc" aria-hidden="true"></i>
                                    <i onclick="sort('sportdesc')" class="fa fa-sort-desc" aria-hidden="true"></i>

                                <?php endif; ?>
                            </th>
                            <th>Tournament Name</th>
                            <th>Competitor
                                <?php if(isset($_GET['sort']) && $this->input->get('sort') == 'competitordesc'): ?>
                                    <i id="nameasc" onclick="sort('comptitorasc')" class="fa fa-sort-asc" aria-hidden="true"></i>
                                <?php elseif(isset($_GET['sort']) && $this->input->get('sort') == 'comptitorasc'): ?>

                                    <i onclick="sort('competitordesc')" class="fa fa-sort-desc" aria-hidden="true"></i>
                                <?php else: ?>
                                    <i id="nameasc" onclick="sort('comptitorasc')" class="fa fa-sort-asc" aria-hidden="true"></i>
                                    <i onclick="sort('competitordesc')" class="fa fa-sort-desc" aria-hidden="true"></i>

                                <?php endif; ?>
                            </th>
                            <th>Best Odds </th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php   $chk=2; if(isset($odds) && !empty($odds)): foreach ($odds as $key=>$values): ?>
                        <tr class="text-center item">
                            <td>
                                <div class="th-checkbox">
                                    <input class="filter-type filled-in checked" type="checkbox" name="delete[]" id="flowers<?php echo $chk; ?>" value="<?php echo $values->id; ?>">
                                    <label for="flowers<?php echo $chk++; ?>" class="lbl-check"><span></span></label>
                                </div>
                            </td>
                            <td><?php echo ++$i; $this->session->set_flashdata('page',$i); ?></td>
                            <td><?php if(isset($values->sport_name) && !empty($values->sport_name)){ echo $values->sport_name; }else{echo 'data not availabe';}  ?></td>
                            <td><?php  if(isset($values->tournament_name) && !empty($values->tournament_name)){ echo $values->tournament_name; }else{echo 'data not availabe';}  ?></td>
                            <td><?php  if(isset($values->competitor_name) && !empty($values->competitor_name)){ echo $values->competitor_name;}else{echo 'data not availabe';}  ?></td>
                            <td><?php  if(isset($values->odds) && !empty($values->odds)){ echo $values->odds; }else{echo 'data not availabe';}  ?></td>
                        </tr>
                    <?php endforeach;
                    else:?>
                    <tr><td align="center" colspan="6">No Result Found!</td></tr>
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

               <div class="clearfix">
                    <div class="left-lg-col">
                        <div class="top-opt-wrap">
                            <ul>
                                <li>
                                <div class="radio-wrap">
                                    <input class="filter-type filled-in select_tour"  type="radio" name="filter" id="sports" value="flowers" checked="true">
                                    <label for="sports" class="lbl-radio"><span></span>Tournaments</label>
                                </div>
                                </li>
                                <li>
                                <div class="radio-wrap">
                                    <input class="filter-type filled-in select_match" type="radio" name="filter" id="sports_game" value="flowers">
                                    <label for="sports_game" class="lbl-radio"><span></span>Matches</label>
                                </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="right-lg-col">

                    </div>
                </div>


                <div class="row m-t-sm">
                    <div class="col-sm-4">
                        <label class="admin-label">Select Sports</label>
                        <div class="commn-select-wrap">
                            <select class="selectpicker" id="select_sports" onchange="getEventDetails(this.value)">
                                <option value="novs">--select--</option>

                                <?php if(isset($sports) && !empty($sports)): foreach($sports as $sport): ?>

                                <option value="<?php echo $sport['sport_id']; ?>"><?php echo $sport['sport_name']; ?></option>

                                <?php endforeach;endif; ?>

                            </select>
                        </div>
                    </div>
                          <input type="hidden" id="set_link" value="">
                    <div class="col-sm-4">
                        <label class="admin-label">Tournament Name</label>
                        <div id="sporteventhere" class="commn-select-wrap">
                            <select  id="event_select" class="selectpicker">
                                <option value="noev">no event</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4 match_class" style="display:none">
                        <label class="admin-label">Tournament Matches</label>
                        <div id="eventeventhere" class="commn-select-wrap">
                            <select  id="match_select" class="selectpicker">
                                <option value="noem">No Match</option>
                            </select>
                        </div>
                    </div>
                        <input type="hidden" id="get_type" value="1">
                    <div class="col-sm-4">
                        <label class="admin-label">&nbsp;</label>
                        <div  class="commn-select-wrap">
                        <button type="button" onclick="get_details()" class="commn-btn add-bttn" title="Details">
                            Get Details</button>
                        </div>
                    </div>


                </div>
             </div>

        <?php echo form_close(); ?>
            
            <script>
                
                function validateOdds() {
                    var dlen=$('[name="delete[]"]:checked').length;

            if(dlen=='0'){

                alert('select atleast one odd');
            }else{

                oddsform.submit();
            }

                    
                }


                function submitforms() {

                    oddsform.submit();

                }
            </script>
<!--data  Wrap close-->

            <div id="myModal-del" class="modal fade" role="dialog">
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
                                <input type="submit" onclick="submitforms()" value="delete" class="commn-btn save" name="decider">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
