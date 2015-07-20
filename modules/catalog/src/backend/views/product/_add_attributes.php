<?php

/* @var $this yii\web\View */
/* @var $attributes im\eav\models\AttributeValue[] */
/* @var $formConfig array */

?>

<?php foreach ($attributes as $attribute) : ?>
    <?= $attribute->getDynamicField($formConfig); ?>
<?php endforeach; ?>