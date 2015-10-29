<?php

/**
 * @var \yii\web\View $this View
 * @var array $options Options
 */

use im\elfinder\ElFinderAsset;
use yii\helpers\Json;

ElFinderAsset::register($this);
$this->registerJs("$('#elfinder').elfinder(" . Json::encode($options).").elfinder('instance');");
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>elFinder 2.0</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div id="elfinder"></div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>