<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $itemContent string */
/* @var $widget im\base\widgets\ListView */
/* @var $key mixed */

$options = $widget->itemContainerOptions;
$tag = ArrayHelper::remove($options, 'tag', 'div');
?>
<?php if ($tag !== false) :
    if (isset($key)) {
        $options['data-key'] = is_array($key) ? json_encode($key, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : (string)$key;
    }
    echo Html::beginTag($tag, $options);
endif ?>
<div class="list-view-item-content">
    <?= $itemContent ?>
    <div class="list-view-item-actions">
        <button type="button" class="btn btn-danger" data-action="remove"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
    </div>
    <?php if ($tag !== false) :
        echo Html::endTag($tag);
    endif ?>
</div>
