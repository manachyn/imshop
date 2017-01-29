<?php

/**
 * Left bottom menu layout.
 *
 * @var \yii\web\View $this View
 */

use im\cms\widgets\Menu;

?>

<?= Menu::widget([
    'location' => 'bottom',
    'itemView' => '@im/pkbnt/views/layouts/top_menu_item',
    'options' => ['class' => 'nav']
]); ?>