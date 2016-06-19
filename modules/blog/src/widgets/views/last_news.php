<?php

/* @var $this yii\web\View */
/* @var $news im\blog\models\News[] */

?>
<?php if ($news) : ?>
<section class="last-news">
    <?php foreach($news as $newsItem) : ?>
    <article>
        <h2><a href="<?= $newsItem->getUrl() ?>"><?= $newsItem->title ?></a></h2>
        <p><?= $newsItem->announce ?></p>
    </article>
    <?php endforeach ?>
</section>
<?php endif ?>