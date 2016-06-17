<?php

namespace im\cms\rest\controllers;

use im\tree\controllers\rest\TreeController;

/**
 * Class PageController
 * @package im\cms\rest\controllers
 */
class PageController extends TreeController
{
    public $modelClass = 'im\cms\models\Page';
}
