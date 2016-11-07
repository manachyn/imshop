<?php
/**
 * Tom menu layout.
 *
 * @var \yii\web\View $this View
 */

use im\cms\widgets\Menu;
use yii\bootstrap\NavBar;

?>

<?php NavBar::begin([
    //'brandLabel' => '<i class="fa fa-home"></i>',
    'options' => ['class' => ['navbar-default', 'navbar-mega', 'navbar-top']],
    'innerContainerOptions' => ['class' => 'container-fluid']
]);
    echo Menu::widget([
        'location' => 'top',
        'itemView' => '@im/pkbnt/views/layouts/top_menu_item',
        'options' => ['class' => 'navbar-nav navbar-right']
    ]);
NavBar::end(); ?>