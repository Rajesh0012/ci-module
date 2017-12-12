
<!--Header close-->
<div class="inner-right-panel">
    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ul>
            <li><a href="#">Sports</a></li>
        </ul>
    </div>
    <!--breadcrumb wrap close-->
    <!--Filter Section -->

<?php if(isset($list) && !empty($list)): if(isset($exception) && !empty($exception)){ echo $exception;} ?>
    <div class="fltr-srch-wrap clearfix">
        <div class="row">
            <div class="col-lg-3 col-sm-3">
                <div class="fltr-field-wrap">

                    <label class="admin-label">Sports</label>
                    <div class="commn-select-wrap">
                        <select name="sports" onchange="Category(this.value)" class="selectpicker">
                            <option >--select Sports--</option>
                            <?php foreach ($list as $key=>$values): ?>
                            <option value="<?php echo $values->sport_id; ?>"><?php echo $values->sport_name; ?></option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="fltr-field-wrap">

                    <label class="admin-label">Category</label>
                    <div id="category" class="commn-select-wrap">
                    <select name="categories" required class="selectpicker">
                       <option>--select--</option>
                    </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="fltr-field-wrap">

                    <label class="admin-label">Tournament</label>
                    <div id="tournament" class="commn-select-wrap">
                        <select  name="tournament" required class="selectpicker">
                            <option>--select--</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <label class="admin-label">&nbsp;</label>
                <div class="fltr-field-wrap">
                    <button onclick="getEvent()" class="commn-btn">Find Event</button>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="fltr-srch-wrap clearfix">
        <div class="row">
            <span>No Records Found!  </span>
        </div></div>
    <?php endif; ?>

    <!--Filter Section Close-->
    <!--Table-->
    <div class="white-wrapper">
        <h2 class="top-heading">Events Organizing</h2>
        <!-- <span class="event-nt-found">No Event found</span> -->
        <div id="eventhere" class="row">

        </div>
    </div>
    <!--Table listing-->
</div>
