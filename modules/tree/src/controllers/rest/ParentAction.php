<?php

namespace im\tree\controllers\rest;

use yii\rest\Action;
use yii\web\NotFoundHttpException;

class ParentAction extends Action
{
    /**
     * Displays parent of a model.
     * @param string $id the primary key of the model.
     * @throws \yii\web\NotFoundHttpException
     * @return \yii\db\ActiveRecordInterface the model being displayed
     */
    public function run($id)
    {
        /** @var $model \im\tree\models\Tree */
        $model = $this->findModel($id);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        $parent = $model->parents(1)->one();

        if (isset($parent)) {
            return $parent;
        } else {
            throw new NotFoundHttpException('Object has no parent');
        }
    }
} 