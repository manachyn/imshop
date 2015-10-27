<?php

use im\cms\models\MenuItem;

/* @var $this yii\web\View */
/* @var $widget \im\cms\widgets\Menu */
/* @var $parent \im\cms\models\MenuItem */
/* @var $level int */

$html = '</ul>';
if ($parent) {
    switch ($parent->items_display) {
        case MenuItem::DISPLAY_DROPDOWN:
            $html = "</ul>\n";
            break;
        case MenuItem::DISPLAY_FULL_WIDTH_DROPDOWN:
            $html = "</ul>\n</div>\n</li>\n</ul>\n";
            break;
        case MenuItem::DISPLAY_GRID:
            $html = "</div>\n</div>\n</li>\n</ul>\n";
            break;
    }
}

echo $html;
