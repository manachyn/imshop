<?php

/**
 * News view.
 * @var \yii\web\View $this
 * @var \im\blog\models\News $model
 * @var \im\blog\models\NewsListPage|null $parentPage
 */

$this->title = $model->title;
if ($parentPage) {
    foreach ($parentPage->getParents() as $parent) {
        $this->params['breadcrumbs'][] = ['label' => $parent->title, 'url' => $parent->getUrl()];
    }
    $this->params['breadcrumbs'][] = ['label' => $parentPage->title, 'url' => $parentPage->getUrl()];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="typography">
    <?= $model->content ?>
</div>