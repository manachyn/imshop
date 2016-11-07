<?php

use im\search\Module;

/* @var $this yii\web\View */
/* @var $action string */

?>

<form class="form-inline" action="<?= $action ?>" data-component="search-form">
    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
    <input type="search" class="form-control" placeholder="<?= Module::t('search-widget', 'Search') ?>" name="text">
</form>

