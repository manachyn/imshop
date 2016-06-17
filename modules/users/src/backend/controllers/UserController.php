<?php

namespace im\users\backend\controllers;

use im\base\controllers\BackendController;
use im\users\backend\models\User;
use im\users\backend\models\UserSearch;
use im\users\models\Profile;
use im\users\Module;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * UserController allows you to administrate users.
 *
 * @property \im\users\Module $module
 */
class UserController extends BackendController
{
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
                        'roles' => ['manageUsers'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['viewUsers'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['createUser'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['updateUser'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['deleteUser'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index'  => ['get'],
                    'view'   => ['get'],
                    'create' => ['get', 'post'],
                    'update' => ['get', 'put', 'post'],
                    'delete' => ['post', 'delete'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = Yii::createObject(UserSearch::className());
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $userComponent = $this->getUserComponent();
        /** @var User $userClass */
        $userClass = $this->module->backendUserModel;
        /** @var User $user */
        $user = Yii::createObject(['class' => $userClass, 'scenario' => $userClass::SCENARIO_CREATE]);
        /** @var Profile $profileClass */
        $profileClass = $this->module->profileModel;
        /** @var Profile $profile */
        $profile = Yii::createObject(['class' => $profileClass, 'scenario' => $profileClass::SCENARIO_CREATE]);
        $user->profile = $profile;

        if (Yii::$app->request->isAjax && $user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return array_merge(ActiveForm::validate($user), ActiveForm::validate($profile));
        }

        if (Yii::$app->request->isPost && ($user = $userComponent->create(Yii::$app->request->post()))) {
            Yii::$app->getSession()->setFlash('success', Module::t('user', 'User has been successfully created.'));
            return $this->redirect(['update', 'id' => $user->id]);
        }

        return $this->render('create', [
            'model' => $user
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'update' page.
     *
     * @param int $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $userComponent = $this->getUserComponent();
        $user = $this->findModel($id);
        $user->scenario = $user::SCENARIO_UPDATE;
        $profile = $user->profile;
        $user->profile->scenario = $profile::SCENARIO_UPDATE;

        if (Yii::$app->request->isAjax && $user->load(Yii::$app->request->post()) && $user->profile->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return array_merge(ActiveForm::validate($user), ActiveForm::validate($user->profile));
        }

        if (Yii::$app->request->isPost && ($user = $userComponent->update($user, Yii::$app->request->post()))) {
            Yii::$app->getSession()->setFlash('success', Module::t('user', 'User has been successfully updated.'));
            return $this->refresh();
        }

        return $this->render('update', [
            'model' => $user
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if ($id == Yii::$app->user->getId()) {
            Yii::$app->getSession()->setFlash('danger', Module::t('user', 'You can not delete your own account'));
        } else {
            if ($this->getUserComponent()->delete($this->findModel($id))) {
                Yii::$app->getSession()->setFlash('success', Module::t('user', 'User has been successfully deleted.'));
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Confirms User.
     *
     * @param int $id
     * @return mixed
     */
    public function actionConfirm($id)
    {;
        if ($this->getUserComponent()->confirm($this->findModel($id))) {
            Yii::$app->getSession()->setFlash('success', Module::t('user', 'User has been confirmed.'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Find the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        /** @var User $user */
        $user = Yii::createObject(['class' => $this->module->backendUserModel]);
        $user = $user::findOne($id);
        if ($user === null) {
            throw new NotFoundHttpException('The requested page does not exist');
        }

        return $user;
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

