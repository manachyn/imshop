<?php

use im\cms\Module;

/* @var $this yii\web\View */

$this->title = Module::t('menu', 'Menu items');
$this->params['subtitle'] = Module::t('menu', 'Menu items list');
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_list');

