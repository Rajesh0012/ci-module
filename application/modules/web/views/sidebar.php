<div class="add-freetip-col">
<div class="inner-add-freetip-col">
    <!--Right Col add block 1-->
    <figure class="right-col-add">
        <img src="<?php echo base_url() ?>assets/images/addidas.png">
    </figure>
    <!--Right Col add block 2-->
    <!--Free Tip Col-->
    <div class="free-tip-col-wrap">
        <h2 class="freetip-title">Free Tips</h2>
        <div class="freetip-list-wrap">
            <ul>
                <?php if(!empty($tips_arr)){
                       foreach($tips_arr as $tip){
                ?>
                <li class="clearfix">
                    <a href="<?php echo SITE_URL; ?>/web/tips/tips_details?id=<?php echo base64_encode($tip['id']); ?>" class="">
                        <figure>
                            <img src="<?php echo !empty($tip['image'])?$tip['image']:''; ?>">
                        </figure>
                        <div class="tip-content-smcol">
                            <p> <?php echo !empty($tip['title'])?$tip['title']:''; ?>
                            </p>
                            <!--<span class="time">5m</span>!-->
                        </div>
                    </a>
                </li>
                <?php
                       }
                } ?>
              
            </ul>
        </div>
        <?php if(!empty($tips_arr)){ ?>
        <div class="view-all-wrap">
            <span><a href="<?php echo SITE_URL; ?>/web/tips"> View All </a></span>
        </div>
        <?php } ?>
    </div>
    <!--Free Tip Col Close-->
    <!--Right Col add block 1-->
    <figure class="right-col-add">
        <img src="<?php echo base_url() ?>assets/images/blk-adidas.png">
    </figure>
    <!--Right Col add block 2-->
</div>
</div>