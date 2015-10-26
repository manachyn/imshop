<?php

/* @var $this yii\web\View */
/* @var $parent \im\cms\models\MenuItem */
/* @var $items \im\cms\models\MenuItem[] */
/* @var $level int */

?>

<?php if ($items) : ?>
    <?= $this->render('menu_items' . ($parent ? '_' . $parent->items_display : '')) ?>
<?php endif ?>