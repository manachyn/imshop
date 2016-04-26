<?php

namespace im\cms\rest\controllers;

use im\tree\controllers\rest\TreeController;

/**
 * Menu item REST controller.
 *
 * @package im\cms\rest\controllers
 */
class MenuItemController extends TreeController
{
    public $modelClass = 'im\cms\models\MenuItem';
} 