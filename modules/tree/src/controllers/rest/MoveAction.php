<?php

namespace im\tree\controllers\rest;

use im\tree\models\Tree;
use yii\rest\UpdateAction;
use Yii;
use yii\web\BadRequestHttpException;

class MoveAction extends UpdateAction
{
    /**
     * @inheritdoc
     */
    public function run($id)
    {
        /* @var $model Tree */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        $model->scenario = $this->scenario;
        $data = Yii::$app->getRequest()->getBodyParams();
        if (isset($data['parent']) && isset($data['position'])) {
            $position = (int) $data['position'];
            /** @var $parent Tree */
            $parent = $this->findModel($data['parent']);
            if ($position === 0) {
                $model->prependTo($parent, false);
            } else {
                $children = $parent->children(1)->andWhere(['not in', 'id', [$model->id]])->all();
                if ($position >= count($children)) {
                    $model->appendTo($parent, false);
                } else {
                    $model->insertBefore($children[$position], false);
                }
            }
        } else {
            throw new BadRequestHttpException('Missing required parameters');
        }

        return $model;
    }
} 