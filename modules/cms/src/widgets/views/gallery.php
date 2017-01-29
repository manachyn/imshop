<?php

use yii\db\ActiveQuery;

/* @var $this yii\web\View */
/* @var $widget im\cms\widgets\GalleryWidget */
/* @var $gallery im\cms\models\Gallery */

/** @var ActiveQuery $items */
$items = $gallery->getItems()->orderBy('sort');
if ($widget->display_count) {
    $items->limit($widget->display_count);
}

?>
<?php if ($gallery) { ?>
    <div class="widget gallery-widget" data-component="gallery">
        <?php if ($widget->title) : ?>
            <h2 class="widget-title"><?= $widget->title ?></h2>
        <?php elseif ($widget->display_title) : ?>
            <h2 class="widget-title"><?= $gallery->name ?></h2>
        <?php endif ?>
        <div class="row">
        <?php foreach ($items->all() as $item) { ?>
            <div class="col-xs-6 col-sm-4 col-md-4 col-lg-3">
                <?php if ($url = $item->getUrl(['w' => 225, 'h' => 126, 'fit' => 'crop'])) : ?>
                    <img src="<?= $url ?>" class="img-responsive">
                <?php endif ?>
                <?php if ($item->caption) : ?>
                    <p><?= $item->caption ?></p>
                <?php endif ?>
            </div>
        <?php } ?>
        </div>
        <?php if ($widget->list_url) : ?>
            <a href="/<?= trim($widget->list_url, '/') ?>"><?= $gallery->name ?></a>
        <?php endif ?>
    </div>
<?php } ?>