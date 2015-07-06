<?php

use im\cms\Module;
use app\themes\admin\widgets\FlashMessages;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model im\cms\models\Page */
/* @var $pageMeta im\cms\models\PageMeta */
/* @var $openGraph im\cms\models\OpenGraph */

$this->title = Module::t('page', 'Batch editing');
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <h4 class="modal-title"><?= $this->title ?></h4>
</div>
<div class="modal-body">
    <?= FlashMessages::widget(); ?>
    <?= $this->render('_batch_form', [
        'page' => $model,
        'pageMeta' => $pageMeta,
        'openGraph' => $openGraph,
        'attributes' => $attributes
    ]) ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= Module::t('page', 'Close') ?></button>
</div>
