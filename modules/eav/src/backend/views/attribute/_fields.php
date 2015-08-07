<?php

/* @var $this yii\web\View */
/* @var $attributes im\eav\models\AttributeValue[] */
/* @var $form yii\widgets\ActiveForm|array */

?>

<?php foreach ($attributes as $attribute) : ?>
    <?= $attribute->getField($form); ?>
<?php endforeach; ?>