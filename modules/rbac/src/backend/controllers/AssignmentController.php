<?php

namespace im\rbac\backend\controllers;

use im\base\controllers\BackendController;
use im\rbac\models\Assignment;
use im\rbac\Module;
use Yii;

/**
 * Class AssignmentController
 * @package im\rbac\backend\controllers
 */
class AssignmentController extends BackendController
{
    /**
     * Assignment roles to user.
     *
     * @param int $id User id
     * @return mixed
     */
    public function actionAssign($id)
    {
        $model = Yii::createObject([
            'class' => Assignment::className(),
            'user_id' => $id
        ]);

        if ($model->load(\Yii::$app->request->post()) && $model->updateAssignments()) {
            Yii::$app->session->setFlash('success', Module::t('assignment', 'User roles has been successfully updated.'));
            return $this->redirect(['assign', 'id' => $id]);
        }

        return $this->render('assign', [
            'model' => $model
        ]);
    }
}

