<?php

use im\blog\Module;

/* @var $this yii\web\View */
/* @var $widget im\blog\widgets\LastNewsWidget */
/* @var $articles im\blog\models\Article[] */

?>
<?php if ($articles) : ?>
<section class="widget last-articles-widget">
    <?php if ($widget->title) { ?>
        <div class="widget-title"><?= $widget->title ?></div>
    <?php } ?>
    <div class="widget-content">
        <?php foreach($articles as $article) : ?>
        <article class="last-articles-item">
            <h2 class="last-articles-item-title"><a href="<?= $article->getUrl() ?>"><?= $article->title ?></a></h2>
        </article>
        <?php endforeach ?>
        <?php if ($widget->list_url) : ?>
            <a href="<?= $widget->list_url ?>" class="btn btn-default btn-sm"><?= Module::t('last-articles-widget', 'All articles') ?></a>
        <?php endif; ?>
    </div>
</section>
<?php endif ?>