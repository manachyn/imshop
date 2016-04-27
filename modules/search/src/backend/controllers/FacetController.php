<?php

namespace im\search\backend\controllers;

use im\base\controllers\BackendController;
use im\forms\components\DynamicActiveForm;
use im\search\components\query\facet\EditableFacetValueInterface;
use im\search\models\Facet;
use im\search\models\FacetSearch;
use im\search\Module;
use Yii;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FacetController implements the CRUD actions for Facet model.
 */
class FacetController extends BackendController
{
//    public function behaviors()
//    {
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['post'],
//                ],
//            ],
//        ];
//    }

    /**
     * Lists all Facet models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FacetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Facet model.
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
     * Creates a new Facet model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @param string $type
     * @return mixed
     */
    public function actionCreate($type = '')
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('searchManager');
        $type = $type ?: Facet::TYPE_DEFAULT;
        /** @var Facet $model */
        $model = $searchManager->getFacetInstance($type);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Module::t('facet', 'Facet has been successfully created.'));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Facet model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Module::t('facet', 'Facet has been successfully saved.'));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Facet model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Module::t('facet', 'Facet has been successfully deleted.'));

        return $this->redirect(['index']);
    }

    /**
     * Return rendered options for attribute select
     * @param $entityType
     * @return string
     */
    public function actionAttributes($entityType)
    {
        $attributes = Facet::getSearchableAttributes($entityType);
        $options = ['prompt' => ''];

        return Html::renderSelectOptions(null, $attributes, $options);
    }

    public function actionAddValue()
    {
        $content = '';
        if ($type = Yii::$app->request->post('itemType')) {
            /** @var \im\search\components\SearchManager $searchManager */
            $searchManager = Yii::$app->get('searchManager');
            $model = $searchManager->getFacetValueInstance($type);
            $itemView = Yii::$app->request->post('itemView');
            if (!$itemView && $model instanceof EditableFacetValueInterface) {
                $itemView = $model->getEditView();
            }
            if ($itemView) {
                $viewParams = Yii::$app->request->post('viewParams', []);
                $viewParams['model'] = $model;
                if (isset($viewParams['form'])) {
                    $viewParams['form'] = new DynamicActiveForm(['config' => $viewParams['form']]);
                }
                if (isset($viewParams['widget'])) {
                    $viewParams['widget'] = (object) $viewParams['widget'];
                }
                $content = $this->renderAjax($itemView, $viewParams);
                if ($itemContainerView = Yii::$app->request->post('itemContainerView')) {
                    $content = $this->renderAjax($itemContainerView, array_merge($viewParams, ['itemContent' => $content]));
                }
            }
        }

        return $content;
    }

    /**
     * Finds the Facet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Facet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Facet::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
