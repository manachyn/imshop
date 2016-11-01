<?php

/* @var $this yii\web\View */
/* @var $widget im\blog\widgets\LastNewsWidget */
/* @var $articles im\blog\models\Article[] */

?>
<?php if ($articles) : ?>
<section class="widget last-articles-widget">
    <?php if ($widget->title) { ?>
        <div class="widget-title"><?= $widget->title ?></div>
    <?php } ?>
    <?php foreach($articles as $article) : ?>
    <article class="last-articles-item">
        <h2 class="last-articles-item-title"><a href="<?= $article->getUrl() ?>"><?= $article->title ?></a></h2>
    </article>
    <?php endforeach ?>
</section>
<?php endif ?>