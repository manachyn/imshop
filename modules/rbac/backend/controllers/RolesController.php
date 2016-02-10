<?php

namespace vova07\rbac\controllers\backend;

use vova07\admin\components\Controller;
use vova07\rbac\models\Role;
use vova07\rbac\Module;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Roles controller.
 */
class RolesController extends Controller
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
                'roles' => ['BackendViewRoles']
            ]
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['create'],
            'roles' => ['BackendCreateRoles']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['update'],
            'roles' => ['BackendUpdateRoles']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete', 'batch-delete'],
            'roles' => ['BackendDeleteRoles']
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
     * Roles list page.
     */
    public function actionIndex()
    {
        $provider = new ArrayDataProvider([
            'allModels' => Yii::$app->authManager->getRoles(),
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
     * Create role page.
     */
    public function actionCreate()
    {
        $model = new Role(['scenario' => 'admin-create']);
        $roleArray = ArrayHelper::map($model->roles, 'name', 'name');
        $ruleArray = ArrayHelper::map($model->rules, 'name', 'name');
        $permissionArray = ArrayHelper::map($model->permissions, 'name', 'name');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->add()) {
                    return $this->redirect(['update', 'name' => $model->name]);
                } else {
                    Yii::$app->session->setFlash('danger', Module::t('rbac', 'BACKEND_ROLES_FLASH_FAIL_ADMIN_CREATE'));
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $model->getErrors();
            }
        }

        return $this->render('create', [
            'model' => $model,
            'roleArray' => $roleArray,
            'ruleArray' => $ruleArray,
            'permissionArray' => $permissionArray,
        ]);
    }

    /**
     * Update role page.
     *
     * @param string $name Role name
     *
     * @return mixed
     */
    public function actionUpdate($name)
    {
        $model = Role::findIdentity($name);
        $model->setScenario('admin-update');
        $roleArray = ArrayHelper::map($model->roles, 'name', 'name');
        $ruleArray = ArrayHelper::map($model->rules, 'name', 'name');
        $permissionArray = ArrayHelper::map($model->permissions, 'name', 'name');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->update()) {
                    return $this->refresh();
                } else {
                    Yii::$app->session->setFlash('danger', Module::t('rbac', 'BACKEND_ROLES_FLASH_FAIL_ADMIN_UPDATE'));
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $model->getErrors();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'roleArray' => $roleArray,
            'ruleArray' => $ruleArray,
            'permissionArray' => $permissionArray
        ]);
    }

    /**
     * Delete role page.
     *
     * @param string $name Role name
     *
     * @return mixed
     */
    public function actionDelete($name)
    {
        $model = $this->findRole($name);

        if (!Yii::$app->authManager->remove($model)) {
            Yii::$app->session->setFlash('danger', Module::t('rbac', 'BACKEND_ROLES_FLASH_FAIL_ADMIN_DELETE'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Delete multiple roles page.
     *
     * @return mixed
     * @throws \yii\web\HttpException 400 if request is invalid
     */
    public function actionBatchDelete()
    {
        if (($names = Yii::$app->request->post('names')) !== null) {
            $auth = Yii::$app->authManager;
            foreach ($names as $item) {
                $role = $this->findRole($item['name']);
                $auth->remove($role);
            }
            return $this->redirect(['index']);
        } else {
            throw new BadRequestHttpException('BACKEND_ROLES_ONLY_POST_IS_ALLOWED');
        }
    }

    /**
     * Find role by name.
     *
     * @param string $name Role name
     *
     * @return \yii\rbac\Role Role
     *
     * @throws HttpException 404 error if role not found
     */
    protected function findRole($name)
    {
        if (($model = Yii::$app->authManager->getRole($name)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, Module::t('rbac', 'BACKEND_ROLES_NOT_FOUND'));
        }
    }
}
