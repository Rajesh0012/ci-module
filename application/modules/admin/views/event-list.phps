<div class="inner-right-panel">
    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ul>
            <li><a href="#">Home Managment</a></li>
        </ul>
    </div>
    <!--breadcrumb wrap close-->
    <div style="color: green; margin-left: 300px;font-size:17px;font-weight:bold" id="addedOdds"></div>


    <!--Table-->
    <div class="white-wrapper">
        <div class="table-responsive clearfix">
            <!--table div-->

            <table id="example" class="list-table table home-bet-table-list" cellspacing="0" width="100%">
                <thead>

                <tr>
                    <th>&nbsp;</th>

                    <th><span class="img-bet-bookie"><img src="<?php echo base_url(); ?>public/admin/images/bet-365.png"></span></th>
                    <th><span class="img-bet-bookie"><img src="<?php echo base_url(); ?>public/admin/images/bet-365.png"></span></th>
                    <th><span class="img-bet-bookie"><img src="<?php echo base_url(); ?>public/admin/images/bet-365.png"></span></th>
                    <th><span class="img-bet-bookie"><img src="<?php echo base_url(); ?>public/admin/images/bet-365.png"></span></th>
                    <th><span class="img-bet-bookie"><img src="<?php echo base_url(); ?>public/admin/images/bet-365.png"></span></th>
                    <th><span class="img-bet-bookie"><img src="<?php echo base_url(); ?>public/admin/images/bet-365.png"></span></th>
                    <th><span class="img-bet-bookie"><img src="<?php echo base_url(); ?>public/admin/images/bet-365.png"></span></th>

                </tr>
                </thead>
                <tbody>
                <?php if(isset($comptitor) && !empty($comptitor)):   foreach ($comptitor as $key=>$values): ?>

                    <tr class="text-center">
                    <td>
                       <?php echo $values->name; ?>
                    </td>

                    <?php $i=1;  foreach ($values->odds as $key2=>$odds): if($i == 8){ break;} ?>

                            <td><?php if(isset($values->odds[$key2]) && !empty($values->odds[$key2])) {echo '<a id="'.$values->category_id.'|=|'.$values->comptitors_id.'|=|'.$values->bookies[$key2].'|=|'.$tournament_id.'|=|'.$match_id.'|=|'.$odds.'" onclick="add(this.id)" href="javascript:void(0)">'.$odds.'</a>';}else{ echo '-';} ?></td>

                    <?php $i++;  endforeach; ?>

                </tr>

                <?php endforeach; else:?>

                    <td colspan="8">
                       No records!
                    </td>
               <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
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
    <!--Table listing-->






</div>



</div>