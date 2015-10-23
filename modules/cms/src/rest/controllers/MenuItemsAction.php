<?php

namespace im\cms\rest\controllers;

use im\cms\models\MenuItem;
use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\Action;


class MenuItemsAction extends Action
{
    /**
     * @var integer level
     */
    public $level;

    /**
     * @var callable a PHP callable that will be called to prepare a data provider that
     * should return a collection of the models. If not set, [[prepareDataProvider()]] will be used instead.
     */
    public $prepareDataProvider;

    /**
     * @param string $id the primary key of the model.
     * @return ActiveDataProvider
     */
    public function run($id)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        return $this->prepareDataProvider($id);
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @param string $id the primary key of the model.
     * @return ActiveDataProvider
     */
    protected function prepareDataProvider($id)
    {
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        return new ActiveDataProvider([
            'query' => ($this->level && $this->level !== 1)
                ? MenuItem::find()->where(['menu_id' => $id])->andWhere(['<', 'depth', $this->level])
                : MenuItem::find()->where(['menu_id' => $id])->roots()
        ]);
    }
}
