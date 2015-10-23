<?php

namespace im\cms\rest\controllers;

use im\cms\models\MenuItem;
use yii\rest\ActiveController;

/**
 * Menu REST controller.
 *
 * @package im\cms\rest\controllers
 */
class MenuController extends ActiveController
{
    public $modelClass = 'im\cms\models\Menu';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return array_merge(parent::actions(), [
            'roots' => [
                'class' => 'im\cms\rest\controllers\MenuItemsAction',
                'modelClass' => MenuItem::className(),
                //'checkAccess' => [$this, 'checkAccess'],
            ],
            'search' => [
                'class' => 'im\cms\rest\controllers\MenuItemsSearchAction',
                'modelClass' => $this->modelClass,
                //'checkAccess' => [$this, 'checkAccess'],
            ],
        ]);
    }
} 