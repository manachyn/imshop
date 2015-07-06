<?php

namespace im\cms\backend\controllers;

use im\cms\components\TemplateManager;
use im\cms\models\Widget;
use im\cms\models\WidgetArea;
use im\cms\Module;
use im\controllers\BackendController;
use im\cms\models\Template;
use im\cms\models\TemplateSearch;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Class TemplateController implements the CRUD actions for Template model.
 *
 * @package im\cms\backend\controllers
 */
class TemplateController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ]);
    }

    /**
     * Lists all Template models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Template model.
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
     * Creates a new Template model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Template();
        /** @var TemplateManager $templateManager */
        $templateManager = \Yii::$app->get('templateManager');
        if ($templateManager->saveTemplate($model, Yii::$app->request->post())) {
            Yii::$app->session->setFlash('success', Module::t('template', 'Template has been successfully created.'));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Template model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        /** @var TemplateManager $templateManager */
        $templateManager = \Yii::$app->get('templateManager');
        if ($templateManager->saveTemplate($model, Yii::$app->request->post())) {
            Yii::$app->session->setFlash('success', Module::t('template', 'Template has been successfully saved.'));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Template model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Module::t('template', 'Template has been successfully deleted.'));

        return $this->redirect(['index']);
    }

    /**
     * Finds the Template model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Template the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Template::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param WidgetArea[] $areas
     * @param boolean $runValidation
     * @return boolean
     */
    protected function saveWidgetAreas($areas, $runValidation = true)
    {
        $saved = true;
        foreach ($areas as $area) {
            if (!$area->save($runValidation)) {
                $saved = false;
            }
        }

        return $saved;
    }

    /**
     * @param Widget[] $widgets
     * @param boolean $runValidation
     * @return boolean
     */
    protected function saveWidgets($widgets, $runValidation = true)
    {
        $saved = true;
        foreach ($widgets as $area) {
            foreach ($area as $widget) {
                /** @var Widget $widget */
                if (!$widget->save($runValidation)) {
                    $saved = false;
                }
            }
        }

        return $saved;
    }
}
