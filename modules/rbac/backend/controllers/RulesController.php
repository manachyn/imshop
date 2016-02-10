<?php

namespace vova07\rbac\controllers\backend;

use app\modules\rbac\Module;
use vova07\admin\components\Controller;
use vova07\rbac\models\Rule;
use vova07\rbac\Module;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Rules controller.
 */
class RulesController extends Controller
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
                'roles' => ['BackendViewRules']
            ]
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['create'],
            'roles' => ['BackendCreateRules']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete', 'batch-delete'],
            'roles' => ['BackendDeleteRules']
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['get'],
                'create' => ['get', 'post'],
                'delete' => ['post', 'delete'],
                'batch-delete' => ['post', 'delete']
            ]
        ];

        return $behaviors;
    }

    /**
     * Rules list page.
     */
    public function actionIndex()
    {
        $provider = new ArrayDataProvider([
            'allModels' => Yii::$app->authManager->getRules(),
            'key' => function ($model) {
                return ['name' => $model->name];
            },
            'sort' => [
                'attributes' => ['name', 'createdAt', 'updatedAt'],
            ]
        ]);

        return $this->render('index', [
            'provider' => $provider
        ]);
    }

    /**
     * Create rule page.
     */
    public function actionCreate()
    {
        $model = new Rule();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->add()) {
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('danger', Module::t('rbac', 'BACKEND_RULES_FLASH_FAIL_ADMIN_CREATE'));
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $model->getErrors();
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Delete rule page.
     *
     * @param string $name Rule name
     *
     * @return mixed
     */
    public function actionDelete($name)
    {
        $model = $this->findRule($name);

        if (!Yii::$app->authManager->remove($model)) {
            Yii::$app->session->setFlash('danger', Module::t('rbac', 'BACKEND_RULES_FLASH_FAIL_ADMIN_DELETE'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Delete multiple rules page.
     *
     * @return mixed
     *
     * @throws \yii\web\HttpException 400 if request is invalid
     */
    public function actionBatchDelete()
    {
        if (($names = Yii::$app->request->post('names')) !== null) {
            $auth = Yii::$app->authManager;
            foreach ($names as $item) {
                $permission = $this->findRule($item['name']);
                $auth->remove($permission);
            }
            return $this->redirect(['index']);
        } else {
            throw new BadRequestHttpException('BACKEND_RULES_ONLY_POST_IS_ALLOWED');
        }
    }

    /**
     * Find rule by name.
     *
     * @param string $name Rule name
     *
     * @return \yii\rbac\Rule Rule
     *
     * @throws HttpException 404 error if rule not found
     */
    protected function findRule($name)
    {
        if (($model = Yii::$app->authManager->getRule($name)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, Module::t('rbac', 'BACKEND_RULES_NOT_FOUND'));
        }
    }
}
