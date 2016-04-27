<?php

namespace im\catalog\models\serialization;

use im\base\components\ModelSerializer;

class CategoryToTreeNode extends ModelSerializer
{
    public function fields()
    {
        return [
            'id' => 'id',
            'text' => 'name',
            //'icon' => 'http://jstree.com/tree.png',
            'children' => function ($model) {
                    return $model->children()->count() > 0;
                }
        ];
    }
}