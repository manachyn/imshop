<?php
/**
 * Tom menu layout.
 *
 * @var \yii\web\View $this View
 */

use im\cms\widgets\Menu;
use yii\bootstrap\NavBar;

$assetsPublishUrl = Yii::$app->assetManager->getPublishedUrl('@im/imshop/assets');
?>

<?php NavBar::begin(['brandLabel' => '<i class="fa fa-home"></i>', 'options' => ['class' => ['navbar-default', 'navbar-mega']]]);
    echo Menu::widget([
        'itemView' => '@im/imshop/views/layouts/top_menu_item',
        'options' => ['class' => 'navbar-nav']
    ]);
NavBar::end(); ?>