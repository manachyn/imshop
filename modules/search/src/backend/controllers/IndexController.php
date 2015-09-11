<?php

namespace im\search\backend\controllers;

use im\base\controllers\BackendController;
use im\search\backend\Module;
use im\search\models\IndexAttribute;
use Yii;
use im\search\models\Index;
use im\search\models\IndexSearch;
use yii\data\ArrayDataProvider;
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

    /**
     * List index attributes.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionAttributes($id)
    {
        if ($data = Yii::$app->request->post('IndexAttribute', [])) {
            IndexAttribute::saveFromData($data);
        }
        /** @var \im\search\components\SearchManager $search */
        $search = Yii::$app->get('search');
        $model = $this->findModel($id);
        $attributes = $search->getIndexAttributes($model->type);
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

    /**
     * Saves indexable attributes from data array.
     *
     * @param $data
     * @return bool whether the attributes were saved
     */
    public function saveIndexableAttributes($data)
    {
        list($indexableCondition, $deleteCondition) = $this->getConditions($data);
        $indexableAttributes = IndexAttribute::find()->where($indexableCondition)->all();
        foreach ($data as $item) {
            if ($item['indexable']) {
                $item['attribute_id'] = $item['attribute_id'] ? (int) $item['attribute_id'] : null;
                $indexable = false;
                foreach ($indexableAttributes as $indexableItem) {
                    if ($item['entity_type'] === $indexableItem['entity_type']
                        && $item['attribute_id'] === $indexableItem['attribute_id']
                        && $item['attribute_name'] === $indexableItem['attribute_name']) {
                        $indexableItem->load($item);
                        $indexable = true;
                        break;
                    }
                }
                if (!$indexable) {
                    $indexableAttributes[] = new IndexAttribute($item);
                }
            }
        }
        $saved = true;
        foreach ($indexableAttributes as $item) {
            if (!$item->save()) {
                $saved = false;
            }
        }
        if ($saved) {
            IndexAttribute::deleteAll($deleteCondition);
        }

        return $saved;
    }

    /**
     * Returns conditions from data array for searching and deleting indexable attributes.
     *
     * @param array $data
     * @return array
     */
    private function getConditions($data)
    {
        $indexableCondition = ['or'];
        $deleteCondition = ['or'];
        foreach ($data as $item) {
            $condition = [
                'entity_type' => $item['entity_type'],
                'attribute_id' => $item['attribute_id'] ?: null,
                'attribute_name' => $item['attribute_name'] ?: ''
            ];
            if ($item['indexable']) {
                $indexableCondition[] = $condition;
            } else {
                $deleteCondition[] = $condition;
            }
        }

        return [$indexableCondition, $deleteCondition];
    }
}
