<?php

namespace app\modules\users\backend\controllers;

use app\modules\backend\components\Controller;
use app\modules\users\models\Profile;
use app\modules\users\Module;
use Yii;
use app\modules\users\models\User;
use app\modules\users\models\UserSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DefaultController implements the CRUD actions for User model.
 */
class DefaultController extends Controller
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
                'roles' => ['BackendViewUsers']
            ]
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['create'],
            'roles' => ['BackendCreateUsers']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['update'],
            'roles' => ['BackendUpdateUsers']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete', 'batch-delete'],
            'roles' => ['BackendDeleteUsers']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['fileapi-upload'],
            'roles' => ['BackendCreateUsers', 'BackendUpdateUsers']
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['post'],
            ],
        ];

        return $behaviors;
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $user = new User(['scenario' => 'create']);
        $profile = new Profile();

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            if ($user->validate() && $profile->validate()) {
                //$user->populateRelation('profile', $profile);
                if ($user->save(false)) {
                    $user->link('profile', $profile);
                    Yii::$app->session->setFlash('success', Module::t('users', 'User has been successfully created.'));
                    return $this->redirect(['update', 'id' => $user->id]);
                } else {
                    Yii::$app->session->setFlash('danger', Module::t('users', 'User has not been saved. Please try again!'));
                    return $this->refresh();
                }
            }
        }

        return $this->render('create', [
            'user' => $user,
            'profile' => $profile
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);
        $profile = $user->profile;

        if ($user->load(Yii::$app->request->post()) && $user->save()) {
            //return $this->redirect(['view', 'id' => $user->id]);
            return $this->refresh();
        } else {
            return $this->render('update', [
                'user' => $user,
                'profile' => $profile
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
