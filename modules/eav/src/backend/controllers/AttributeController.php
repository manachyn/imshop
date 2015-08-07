<?php

namespace im\eav\backend\controllers;

use im\base\controllers\BackendController;
use im\catalog\Module;
use im\eav\components\EavTrait;
use im\eav\models\Attribute;
use im\eav\models\AttributeSearch;
use im\eav\models\AttributeValue;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AttributeController implements the CRUD actions for Attribute model.
 */
class AttributeController extends BackendController
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
     * Lists all Attribute models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AttributeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Attribute model.
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
     * Creates a new Attribute model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Attribute();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Module::t('attribute', 'Attribute has been successfully created.'));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Attribute model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Module::t('attribute', 'Attribute has been successfully saved.'));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Attribute model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Module::t('attribute', 'Attribute has been successfully deleted.'));

        return $this->redirect(['index']);
    }

    /**
     * Returns rendered form fields for requested attributes.
     *
     * @return string
     */
    public function actionFields()
    {
        if (($attributes = Yii::$app->request->post('attributes')) && ($form = Yii::$app->request->post('form'))) {
            $attributes = Attribute::findAll(['id' => $attributes]);
            /** @var EavTrait $modelClass */
            $modelClass = Yii::$app->request->post('modelClass');
            $values = [];
            foreach ($attributes as $attribute) {
                $value = $modelClass ? $modelClass::getAttributeValueInstance() : AttributeValue::getInstance();
                $value->setEAttribute($attribute);
                $values[$attribute->getName()] = $value;
            }
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_fields', [
                    'attributes' => $values,
                    'form' => $form
                ]);
            }
        }
    }

    /**
     * Finds the Attribute model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Attribute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Attribute::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
