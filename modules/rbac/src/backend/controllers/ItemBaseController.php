<?php

namespace im\rbac\backend\controllers;

use im\base\controllers\BackendController;
use im\rbac\models\AuthItem;
use im\rbac\models\AuthItemSearch;
use im\rbac\Module;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\bootstrap\ActiveForm;
use yii\rbac\Item;
use yii\web\Response;

/**
 * Class ItemBaseController
 * @package im\rbac\backend\controllers
 */
abstract class ItemBaseController extends BackendController
{
    /**
     * @param string $name
     * @return Item
     */
    abstract protected function getItem($name);

    /**
     * @var int
     */
    protected $type;
    
    /**
     * @var string
     */
    protected $modelClass;

    /**
     * @var string
     */
    protected $successCreate;

    /**
     * @var string
     */
    protected $successUpdate;

    /**
     * @var string
     */
    protected $successDelete;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->modelClass === null) {
            throw new InvalidConfigException('Model class should be set');
        }
        if ($this->type === null) {
            throw new InvalidConfigException('Auth item type should be set');
        }
        $this->successCreate = Module::t('auth-item', 'Item has been successfully created.');
        $this->successUpdate = Module::t('auth-item', 'Item has been successfully saved.');
        $this->successDelete = Module::t('auth-item', 'Item has been successfully deleted.');
    }

    /**
     * Lists all auth items.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthItemSearch($this->type);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new auth item.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /** @var AuthItem $model */
        $model = Yii::createObject([
            'class' => $this->modelClass,
            'scenario' => 'create'
        ]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', $this->successCreate);
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing auth item.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param string $name
     * @return mixed
     */
    public function actionUpdate($name)
    {
        $item  = $this->getItem($name);
        /** @var AuthItem $model */
        $model = Yii::createObject([
            'class' => $this->modelClass,
            'scenario' => 'update',
            'item' => $item
        ]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', $this->successUpdate);
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing auth item.
     * @param string $name
     * @return mixed
     */
    public function actionDelete($name)
    {
        $item = $this->getItem($name);
        Yii::$app->authManager->remove($item);
        Yii::$app->session->setFlash('success', $this->successDelete);

        return $this->redirect(['index']);
    }
}

