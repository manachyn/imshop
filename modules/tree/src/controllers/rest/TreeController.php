<?php

namespace im\tree\controllers\rest;

use yii\rest\ActiveController;

abstract class TreeController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return array_merge(parent::actions(), [
            'roots' => [
                'class' => 'im\tree\controllers\rest\RootsAction',
                'modelClass' => $this->modelClass,
                //'checkAccess' => [$this, 'checkAccess'],
            ],
            'leaves' => [
                'class' => 'im\tree\controllers\rest\LeavesAction',
                'modelClass' => $this->modelClass,
                //'checkAccess' => [$this, 'checkAccess'],
            ],
            'descendants' => [
                'class' => 'im\tree\controllers\rest\DescendantsAction',
                'modelClass' => $this->modelClass,
                //'checkAccess' => [$this, 'checkAccess'],
            ],
            'children' => [
                'class' => 'im\tree\controllers\rest\DescendantsAction',
                'modelClass' => $this->modelClass,
                'level' => 1
                //'checkAccess' => [$this, 'checkAccess'],
            ],
            'ancestors' => [
                'class' => 'im\tree\controllers\rest\AncestorsAction',
                'modelClass' => $this->modelClass,
                //'checkAccess' => [$this, 'checkAccess'],
            ],
            'parent' => [
                'class' => 'im\tree\controllers\rest\ParentAction',
                'modelClass' => $this->modelClass,
                //'checkAccess' => [$this, 'checkAccess'],
            ],
            'move' => [
                'class' => 'im\tree\controllers\rest\MoveAction',
                'modelClass' => $this->modelClass,
                //'checkAccess' => [$this, 'checkAccess'],
            ],
            'search' => [
                'class' => 'im\tree\controllers\rest\SearchAction',
                'modelClass' => $this->modelClass,
                //'checkAccess' => [$this, 'checkAccess'],
            ],
        ]);
    }
} 