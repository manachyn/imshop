<?php

namespace app\modules\rbac\backend\controllers;

use app\modules\backend\components\Controller;
use app\modules\rbac\models\Permission;
use app\modules\rbac\Module;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Permissions controller.
 */
class PermissionsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => ['BackendViewPermissions']
            ]
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['create'],
            'roles' => ['BackendCreatePermissions']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['update'],
            'roles' => ['BackendUpdatePermissions']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete', 'batch-delete'],
            'roles' => ['BackendDeletePermissions']
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['get'],
                'create' => ['get', 'post'],
                'update' => ['get', 'put', 'post'],
                'delete' => ['post', 'delete'],
                'batch-delete' => ['post', 'delete']
            ]
        ];

        return $behaviors;
    }

    /**
     * Permissions list page.
     */
    public function actionIndex()
    {
        $provider = new ArrayDataProvider([
            'allModels' => Yii::$app->authManager->getPermissions(),
            'key' => function ($model) {
                return ['name' => $model->name];
            },
            'sort' => [
                'attributes' => ['name', 'ruleName', 'createdAt', 'updatedAt'],
            ]
        ]);

        return $this->render('index', [
            'provider' => $provider
        ]);
    }

    /**
     * Create permission page.
     */
    public function actionCreate()
    {
        $model = new Permission(['scenario' => 'admin-create']);
        $permissionArray = ArrayHelper::map($model->permissions, 'name', 'name');
        $ruleArray = ArrayHelper::map($model->rules, 'name', 'name');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->add()) {
                    return $this->redirect(['update', 'name' => $model->name]);
                } else {
                    Yii::$app->session->setFlash('danger', Module::t('rbac', 'BACKEND_PERMISSIONS_FLASH_FAIL_ADMIN_CREATE'));
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $model->getErrors();
            }
        }

        return $this->render('create', [
            'model' => $model,
            'permissionArray' => $permissionArray,
            'ruleArray' => $ruleArray
        ]);
    }

    /**
     * Update permission page.
     *
     * @param string $name Permission name
     *
     * @return mixed
     */
    public function actionUpdate($name)
    {
        $model = Permission::findIdentity($name);
        $model->setScenario('admin-update');
        $permissionArray = ArrayHelper::map($model->permissions, 'name', 'name');
        $ruleArray = ArrayHelper::map($model->rules, 'name', 'name');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->update()) {
                    return $this->refresh();
                } else {
                    Yii::$app->session->setFlash('danger', Module::t('rbac', 'BACKEND_PERMISSIONS_FLASH_FAIL_ADMIN_UPDATE'));
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $model->getErrors();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'permissionArray' => $permissionArray,
            'ruleArray' => $ruleArray
        ]);
    }

    /**
     * Delete permission page.
     *
     * @param string $name Permission name
     *
     * @return mixed
     */
    public function actionDelete($name)
    {
        $model = $this->findPermission($name);

        if (!Yii::$app->authManager->remove($model)) {
            Yii::$app->session->setFlash('danger', Module::t('rbac', 'BACKEND_PERMISSIONS_FLASH_FAIL_ADMIN_DELETE'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Delete multiple permissions page.
     *
     * @return mixed
     * @throws \yii\web\HttpException 400 if request is invalid
     */
    public function actionBatchDelete()
    {
        if (($names = Yii::$app->request->post('names')) !== null) {
            $auth = Yii::$app->authManager;
            foreach ($names as $item) {
                $permission = $this->findPermission($item['name']);
                $auth->remove($permission);
            }
            return $this->redirect(['index']);
        } else {
            throw new BadRequestHttpException('BACKEND_PERMISSIONS_ONLY_POST_IS_ALLOWED');
        }
    }

    /**
     * Find permission by name.
     *
     * @param string $name Permission name
     *
     * @return \yii\rbac\Permission Permission
     *
     * @throws HttpException 404 error if role not found
     */
    protected function findPermission($name)
    {
        if (($model = Yii::$app->authManager->getPermission($name)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, Module::t('rbac', 'BACKEND_PERMISSIONS_NOT_FOUND'));
        }
    }
}
