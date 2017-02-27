<?php

use im\blog\Module;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $widget im\blog\widgets\LastNewsWidget */
/* @var $news im\blog\models\News[] */

?>
<?php if ($news) : ?>
<section class="widget last-news-widget">
    <?php if ($widget->title) { ?>
        <div class="widget-title last-news-widget-title"><?= $widget->title ?></div>
    <?php } ?>
    <div class="widget-content">
    <?php foreach($news as $newsItem) : ?>
    <article class="last-news-item">
        <?php if ($image = $newsItem->image) { ?>
        <div class="last-news-item-thumb">
            <img src="<?= $image->getUrl(['w' => 100]) ?>">
        </div>
        <?php } ?>
        <h2 class="last-news-item-title"><a href="<?= $newsItem->getUrl() ?>"><?= $newsItem->title ?></a></h2>
        <div class="last-news-item-date"><?= date('d.m.Y', $newsItem->created_at) ?></div>
        <p class="last-news-item-announce clearfix"><?= StringHelper::truncateWords($newsItem->announce, 5) ?></p>
    </article>
    <?php endforeach ?>
    <?php if ($widget->list_url) : ?>
        <a href="<?= $widget->list_url ?>" class="btn btn-default btn-sm"><?= Module::t('last-news-widget', 'All news') ?></a>
    <?php endif; ?>
    </div>
</section>
<?php endif ?>