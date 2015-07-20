<?php

namespace im\catalog\rest\controllers;

use im\tree\controllers\rest\TreeController;

class CategoryController extends TreeController
{
    public $modelClass = 'im\catalog\models\Category';
} 