<?php

/* @var $this yii\web\View */
/* @var $articles im\blog\models\Article[] */

?>
<?php if ($articles) : ?>
<section class="last-articles">
    <?php foreach($articles as $article) : ?>
    <article>
        <h2><a href="<?= $article->getUrl() ?>"><?= $article->title ?></a></h2>
    </article>
    <?php endforeach ?>
</section>
<?php endif ?>