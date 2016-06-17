<?php

namespace im\users\controllers;

use im\users\models\ProfileForm;
use im\users\Module;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class ProfileController
 * @property \im\users\Module $module
 * @package im\users\controllers
 */
class ProfileController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'update';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * Update user.
     *
     * @return mixed
     */
    public function actionUpdate()
    {
        $userComponent = $this->getUserComponent();
        /** @var ProfileForm $model */
        $model = Yii::createObject($this->module->profileForm);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $userComponent->updateProfile($model)) {
            Yii::$app->session->setFlash('profile', Module::t('profile', 'Your account details have been updated.'));
            return $this->refresh();
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Returns user component.
     *
     * @return \im\users\components\UserComponent
     */
    protected function getUserComponent()
    {
        return Yii::$app->user;
    }
}
