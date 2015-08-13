<?php

namespace im\search\backend\controllers;

use im\base\controllers\BackendController;
use im\search\backend\Module;
use im\search\components\SearchProvider;
use im\search\models\IndexAttribute;
use Yii;
use im\search\models\Index;
use im\search\models\IndexSearch;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * IndexController implements the CRUD actions for Index model.
 */
class IndexController extends BackendController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Index models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new IndexSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Index model.
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
     * Creates a new Index model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Index();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Module::t('index', 'Index has been successfully created.'));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Index model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Module::t('index', 'Index has been successfully saved.'));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Index model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Module::t('index', 'Index has been successfully deleted.'));

        return $this->redirect(['index']);
    }

    public function actionAttributes($id)
    {
        $condition = [];
        $deleteCondition = ['or'];
        $toDelete = [];
        $data = Yii::$app->request->post('IndexAttribute', []);
        if ($data) {
            foreach ($data as $item) {
                if ($item['indexable']) {
                    $condition = ['or', $condition, [
                        'entity_type' => $item['entity_type'],
                        'attribute_id' => $item['attribute_id'] ?: null,
                        'attribute_name' => $item['attribute_name'] ?: ''
                    ]];
                    $toDelete[] = [
                        'entity_type' => $item['entity_type'],
                        'attribute_id' => $item['attribute_id'] ?: null,
                        'attribute_name' => $item['attribute_name'] ?: ''
                    ];
                }
                $deleteCondition[] = [
                    'entity_type' => $item['entity_type'],
                    'attribute_id' => $item['attribute_id'] ?: null,
                    'attribute_name' => $item['attribute_name'] ?: ''
                ];

            }
            //$deleteCondition = ['and', $deleteCondition, ['not in', ['entity_type', 'attribute_id', 'attribute_name'], $toDelete]];



            $inbd = IndexAttribute::find()->where($condition)->indexBy('id')->all();

            $deleteCondition = ['and', $deleteCondition, ['not in', 'id', array_keys($inbd)]];

            IndexAttribute::deleteAll($deleteCondition);
        }


        /** @var \im\search\components\Search $search */
        $search = Yii::$app->get('search');
        $model = $this->findModel($id);
        $attributes = $search->getIndexAttributes($model->entity_type);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $attributes,
            'sort' => [
                'attributes' => ['name', 'label', 'indexable']
            ]
        ]);

        return $this->render('attributes', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Finds the Index model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Index the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Index::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getIndexableAttributes($data)
    {

    }
}
