<?php

namespace im\eav\backend\controllers;

use im\base\controllers\BackendController;
use im\catalog\Module;
use im\eav\models\Value;
use im\eav\models\ValueSearch;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ValueController implements the CRUD actions for Value model.
 */
class ValueController extends BackendController
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
     * Lists all Value models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ValueSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Value model.
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
     * Creates a new Value model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Value();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Module::t('value', 'Value has been successfully created.'));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Value model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Module::t('value', 'Value has been successfully saved.'));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Value model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Module::t('value', 'Value has been successfully deleted.'));

        return $this->redirect(['index']);
    }

    /**
     * Finds the Value model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Value the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Value::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
