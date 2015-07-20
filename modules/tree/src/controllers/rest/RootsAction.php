<?php

namespace im\tree\controllers\rest;

use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\IndexAction;

class RootsAction extends IndexAction
{
    /**
     * @inheritdoc
     */
    protected function prepareDataProvider()
    {
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        /* @var $modelClass \im\tree\models\Tree */
        $modelClass = $this->modelClass;

        return new ActiveDataProvider([
            'query' => $modelClass::find()->roots()
        ]);
    }
}
