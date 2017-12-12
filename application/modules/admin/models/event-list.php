<div class="inner-right-panel">
    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ul>
            <li><a href="#">Home Managment</a></li>
        </ul>
        <a href="<?php echo SITE_URL; ?>/admin/Home/homePage">Back</a>
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
                        <?php
                        if (!empty($book_arr)) {

                            foreach ($book_arr as $book) {
                                ?>
                                <th><span class="img-bet-bookie"><img src="<?php echo !empty($book['book_images']) ? $book['book_images'] : BASE_URL . 'public/admin/images/bet-365-v.png'; ?>"></span></th>

                                <?php
                            }
                        }
                        ?>


                    </tr>
                </thead>
                <tbody>
<?php if (isset($comptitor) && !empty($comptitor)): foreach ($comptitor as $key => $values): ?>

                            <tr class="text-center">
                                <td>
                                    <?php echo $values->compitetor_name;
                                    ?>
                                </td>


                                <?php
                                if (in_array('sr:book:6', $values->bookies)) {
                                    if (isset($values->odds[$key]) && !empty($values->odds[$key])) {
                                        if (isset($values->comptitors_id) && !empty($values->comptitors_id))
                                            echo '<td><span class="view-odds"><a id="' . $values->category_id . '|=|' . $values->comptitors_id . '|=|' . $values->bookies[$key] . '|=|' . $tournament_id . '|=|' . $match_id . '|=|' . $values->odds[$key] . '" onclick="add(this.id)" href="javascript:void(0)">' . $values->odds[$key] . '</a></span></td>';
                                        else
                                            echo '<td><span class="view-odds"><a id="' . $values->category_id . '|=|' . $values->bookies[$key] . '|=|' . $tournament_id . '|=|' . $match_id . '|=|' . $values->odds[$key] . '" onclick="add(this.id)" href="javascript:void(0)">' . $values->odds[$key] . '</a></span></td>';
                                    } else {
                                        echo "<td>-</td>";
                                    }
                                } else {
                                    echo "<td>-</td>";
                                }
                                if (in_array('sr:book:7', $values->bookies)) {
                                    if (isset($values->odds[$key]) && !empty($values->odds[$key])) {
                                        if (isset($values->comptitors_id) && !empty($values->comptitors_id))
                                            echo '<td><span class="view-odds"><a id="' . $values->category_id . '|=|' . $values->comptitors_id . '|=|' . $values->bookies[$key] . '|=|' . $tournament_id . '|=|' . $match_id . '|=|' . $values->odds[$key] . '" onclick="add(this.id)" href="javascript:void(0)">' . $values->odds[$key] . '</a></span></td>';
                                        else
                                            echo '<td><span class="view-odds"><a id="' . $values->category_id . '|=|' . $values->bookies[$key] . '|=|' . $tournament_id . '|=|' . $match_id . '|=|' . $values->odds[$key] . '" onclick="add(this.id)" href="javascript:void(0)">' . $values->odds[$key] . '</a></span></td>';
                                    } else {
                                        echo "<td>-</td>";
                                    }
                                } else {
                                    echo "<td>-</td>";
                                }
                                if (in_array('sr:book:12', $values->bookies)) {
                                    if (isset($values->odds[$key]) && !empty($values->odds[$key])) {
                                        if (isset($values->comptitors_id) && !empty($values->comptitors_id))
                                            echo '<td><span class="view-odds"><a id="' . $values->category_id . '|=|' . $values->comptitors_id . '|=|' . $values->bookies[$key] . '|=|' . $tournament_id . '|=|' . $match_id . '|=|' . $values->odds[$key] . '" onclick="add(this.id)" href="javascript:void(0)">' . $values->odds[$key] . '</a></span></td>';
                                        else
                                            echo '<td><span class="view-odds"><a id="' . $values->category_id . '|=|' . $values->bookies[$key] . '|=|' . $tournament_id . '|=|' . $match_id . '|=|' . $values->odds[$key] . '" onclick="add(this.id)" href="javascript:void(0)">' . $values->odds[$key] . '</a></span></td>';
                                    } else {
                                        echo "<td>-</td>";
                                    }
                                } else {
                                    echo "<td>-</td>";
                                }
                                if (in_array('sr:book:28', $values->bookies)) {
                                    if (isset($values->odds[$key]) && !empty($values->odds[$key])) {
                                        if (isset($values->comptitors_id) && !empty($values->comptitors_id))
                                            echo '<td><span class="view-odds"><a id="' . $values->category_id . '|=|' . $values->comptitors_id . '|=|' . $values->bookies[$key] . '|=|' . $tournament_id . '|=|' . $match_id . '|=|' . $values->odds[$key] . '" onclick="add(this.id)" href="javascript:void(0)">' . $values->odds[$key] . '</a></span></td>';
                                        else
                                            echo '<td><span class="view-odds"><a id="' . $values->category_id . '|=|' . $values->bookies[$key] . '|=|' . $tournament_id . '|=|' . $match_id . '|=|' . $values->odds[$key] . '" onclick="add(this.id)" href="javascript:void(0)">' . $values->odds[$key] . '</a></span></td>';
                                    } else {
                                        echo "<td>-</td>";
                                    }
                                } else {
                                    echo "<td>-</td>";
                                }
                                if (in_array('sr:book:74', $values->bookies)) {
                                    if (isset($values->odds[$key]) && !empty($values->odds[$key])) {
                                        if (isset($values->comptitors_id) && !empty($values->comptitors_id))
                                            echo '<td><span class="view-odds"><a id="' . $values->category_id . '|=|' . $values->comptitors_id . '|=|' . $values->bookies[$key] . '|=|' . $tournament_id . '|=|' . $match_id . '|=|' . $values->odds[$key] . '" onclick="add(this.id)" href="javascript:void(0)">' . $values->odds[$key] . '</a></span></td>';
                                        else
                                            echo '<td><span class="view-odds"><a id="' . $values->category_id . '|=|' . $values->bookies[$key] . '|=|' . $tournament_id . '|=|' . $match_id . '|=|' . $values->odds[$key] . '" onclick="add(this.id)" href="javascript:void(0)">' . $values->odds[$key] . '</a></span></td>';
                                    } else {
                                        echo "<td>-</td>";
                                    }
                                } else {
                                    echo "<td>-</td>";
                                }
                                if (in_array('sr:book:459', $values->bookies)) {
                                    if (isset($values->odds[$key]) && !empty($values->odds[$key])) {
                                        if (isset($values->comptitors_id) && !empty($values->comptitors_id))
                                            echo '<td><span class="view-odds"><a id="' . $values->category_id . '|=|' . $values->comptitors_id . '|=|' . $values->bookies[$key] . '|=|' . $tournament_id . '|=|' . $match_id . '|=|' . $values->odds[$key] . '" onclick="add(this.id)" href="javascript:void(0)">' . $values->odds[$key] . '</a></span></td>';
                                        else
                                            echo '<td><span class="view-odds"><a id="' . $values->category_id . '|=|' . $values->bookies[$key] . '|=|' . $tournament_id . '|=|' . $match_id . '|=|' . $values->odds[$key] . '" onclick="add(this.id)" href="javascript:void(0)">' . $values->odds[$key] . '</a></span></td>';
                                    } else {
                                        echo "<td>-</td>";
                                    }
                                } else {
                                    echo "<td>-</td>";
                                }
                                if (in_array('sr:book:988', $values->bookies)) {
                                    if (isset($values->odds[$key]) && !empty($values->odds[$key])) {
                                        if (isset($values->comptitors_id) && !empty($values->comptitors_id))
                                            echo '<td><span class="view-odds"><a id="' . $values->category_id . '|=|' . $values->comptitors_id . '|=|' . $values->bookies[$key] . '|=|' . $tournament_id . '|=|' . $match_id . '|=|' . $values->odds[$key] . '" onclick="add(this.id)" href="javascript:void(0)">' . $values->odds[$key] . '</a></span></td>';
                                        else
                                            echo '<td><span class="view-odds"><a id="' . $values->category_id . '|=|' . $values->bookies[$key] . '|=|' . $tournament_id . '|=|' . $match_id . '|=|' . $values->odds[$key] . '" onclick="add(this.id)" href="javascript:void(0)">' . $values->odds[$key] . '</a></span></td>';
                                    } else {
                                        echo "<td>-</td>";
                                    }
                                } else {
                                    echo "<td>-</td>";
                                }
                                if (in_array('sr:book:9', $values->bookies)) {
                                    if (isset($values->odds[$key]) && !empty($values->odds[$key])) {
                                        if (isset($values->comptitors_id) && !empty($values->comptitors_id))
                                            echo '<td><span class="view-odds"><a id="' . $values->category_id . '|=|' . $values->comptitors_id . '|=|' . $values->bookies[$key] . '|=|' . $tournament_id . '|=|' . $match_id . '|=|' . $values->odds[$key] . '" onclick="add(this.id)" href="javascript:void(0)">' . $values->odds[$key] . '</a></span></td>';
                                        else
                                            echo '<td><span class="view-odds"><a id="' . $values->category_id . '|=|' . $values->bookies[$key] . '|=|' . $tournament_id . '|=|' . $match_id . '|=|' . $values->odds[$key] . '" onclick="add(this.id)" href="javascript:void(0)">' . $values->odds[$key] . '</a></span></td>';
                                    } else {
                                        echo "<td>-</td>";
                                    }
                                } else {
                                    echo "<td>-</td>";
                                }
                                if (in_array('sr:book:10643', $values->bookies)) {
                                    if (isset($values->odds[$key]) && !empty($values->odds[$key])) {
                                        if (isset($values->comptitors_id) && !empty($values->comptitors_id))
                                            echo '<td><span class="view-odds"><a id="' . $values->category_id . '|=|' . $values->comptitors_id . '|=|' . $values->bookies[$key] . '|=|' . $tournament_id . '|=|' . $match_id . '|=|' . $values->odds[$key] . '" onclick="add(this.id)" href="javascript:void(0)">' . $values->odds[$key] . '</a></span></td>';
                                        else
                                            echo '<td><span class="view-odds"><a id="' . $values->category_id . '|=|' . $values->bookies[$key] . '|=|' . $tournament_id . '|=|' . $match_id . '|=|' . $values->odds[$key] . '" onclick="add(this.id)" href="javascript:void(0)">' . $values->odds[$key] . '</a></span></td>';
                                    } else {
                                        echo '<td>-</td>';
                                    }
                                } else {
                                    echo "<td>-</td>";
                                }
                                ?>


                                <?php //$i++;  //endforeach; ?>
                                <!-- --><?php /* $i=1; for($j=0;$j<9;$j++){
                          if(!empty($values->odds[$j]) && $values->bookies[$j] == 'sr:book:6')
                          {
                          echo  '<td><a id="'.$values->category_id.'|=|'.$values->comptitors_id.'|=|'.$values->bookies[$j].'|=|'.$tournament_id.'|=|'.$match_id.'|=|'.$values->odds[$j].'" onclick="add(this.id)" href="javascript:void(0)">'.$values->odds[$j].'</a></td>';
                          }
                          else{
                          echo "<td>-</td>";
                          }
                          } */ ?>
                            </tr>

                            <?php endforeach;
                        else: ?>

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
if (isset($pagination) && !empty($pagination)) {
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