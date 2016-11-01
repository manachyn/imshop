<?php
/**
 * Tom menu layout.
 *
 * @var \yii\web\View $this View
 */

use im\cms\widgets\Menu;
use yii\bootstrap\NavBar;

?>

<?php NavBar::begin(['brandLabel' => '<i class="fa fa-home"></i>', 'options' => ['class' => ['navbar-default', 'navbar-mega', 'navbar-top']]]);
    echo Menu::widget([
        'location' => 'top',
        'itemView' => '@im/pkbnt/views/layouts/top_menu_item',
        'options' => ['class' => 'navbar-nav']
    ]);
NavBar::end(); ?>