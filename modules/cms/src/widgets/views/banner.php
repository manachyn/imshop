<?php

/* @var $this yii\web\View */
/* @var $widget im\cms\widgets\BannerWidget */
/* @var $banner im\cms\models\Banner */

?>
<?php if ($banner) { ?>
<div class="widget banner-widget owl-carousel owl-theme" data-component="carousel">
    <?php foreach ($banner->items as $item) { ?>
    <div style="background-image: url(<?= $item->getUrl() ?>)" class="item">
        <?php if ($item->caption) { ?>
            <div class="carousel-caption container">
                <div class="row">
                    <div class="col-sm-12">
                        <?= $item->caption ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<?php } ?>