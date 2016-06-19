<?php

namespace im\base\controllers;

use im\base\models\ModelAttribute;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

/**
 * Class CrudController implements the base CRUD actions for model.
 *
 * @package im\base\controllers
 */
abstract class CrudController extends BackendController
{
    /**
     * @var string
     */
    public $successCreate;

    /**
     * @var string
     */
    public $errorCreate;

    /**
     * @var string
     */
    public $successUpdate;

    /**
     * @var string
     */
    public $successBatchUpdate;

    /**
     * @var string
     */
    public $errorUpdate;

    /**
     * @var string
     */
    public $successDelete;

    /**
     * @var string
     */
    public $successBatchDelete;

    /**
     * @var string
     */
    public $errorNotFound;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!isset($this->successCreate)) {
            $this->successCreate = Yii::t('app', 'Model has been successfully created.');
        }
        if (!isset($this->errorCreate)) {
            $this->errorCreate = Yii::t('app', 'Model has not been created. Please try again!');
        }
        if (!isset($this->successUpdate)) {
            $this->successUpdate = Yii::t('app', 'Model has been successfully saved.');
        }
        if (!isset($this->successBatchUpdate)) {
            $this->successBatchUpdate = Yii::t('app', '{count} Models have been successfully saved.');
        }
        if (!isset($this->errorUpdate)) {
            $this->errorUpdate = Yii::t('app', 'Model has not been saved. Please try again!');
        }
        if (!isset($this->successDelete)) {
            $this->successDelete = Yii::t('app', 'Model has been successfully deleted.');
        }
        if (!isset($this->successBatchDelete)) {
            $this->successBatchDelete = Yii::t('app', 'Models have been successfully deleted.');
        }
    }

    /**
     * @return string
     */
    abstract protected function getModelClass();

    /**
     * @return string
     */
    abstract protected function getSearchModelClass();

    /**
     * Lists all models.
     * @return mixed
     */
    public function actionIndex()
    {
        $modelClass = $this->getSearchModelClass();
        $searchModel = new $modelClass;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = $this->createModel();
        $models = array_merge(['model' => $model], $this->getRelatedModels($model));
        if ($this->loadModels($models, Yii::$app->request->post())) {
            if ($this->validateModels($models)) {
                if ($this->saveModels($models, true)) {
                    $this->setFlashMessages('create', false, $models);
                    if (($result = $this->afterSave($models, true)) !== null) {
                        return $result;
                    }
                }
                else {
                    $this->setFlashMessages('create', true, $models);
                    if (($result = $this->onSaveError($models, true)) !== null) {
                        return $result;
                    }
                }
            } else {
                $this->onValidateError($models);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', $models);
        } else {
            return $this->render('create', $models);
        }
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModels($id);
        $models = array_merge(['model' => $model], $this->getRelatedModels($model));
        if ($this->loadModels($models, Yii::$app->request->post())) {
            if ($this->validateModels($models)) {
                if ($this->saveModels($models)) {
                    $this->setFlashMessages('update', false, $models);
                    if (($result = $this->afterSave($models)) !== null) {
                        return $result;
                    }
                }
                else {
                    $this->setFlashMessages('update', true, $models);
                    if (($result = $this->onSaveError($models)) !== null) {
                        return $result;
                    }
                }
            } else {
                $this->onValidateError($models);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', $models);
        } else {
            return $this->render('update', $models);
        }
    }

    public function actionBatchUpdate()
    {
        if (($ids = Yii::$app->request->post('ids')) !== null) {
            $data = Yii::$app->request->post();
            $models = $this->findModelsToBatchUpdate($ids, $data);
            $count = 0;
            foreach ($models as $model) {
                $modelsToUpdate = array_merge(['model' => $model], $this->getRelatedModels($model, $data));
                if ($this->loadModels($modelsToUpdate, Yii::$app->request->post())) {
                    if ($this->validateModels($modelsToUpdate)) {
                        if ($this->saveModels($modelsToUpdate)) {
                            $count++;
                            $this->afterSave($modelsToUpdate);
                        }
                    }
                }
            }
            $this->setFlashMessages('batchUpdate', false, ['placeholders' => ['count' => $count]]);
            return $this->redirect(['index']);
        }
        else {
            $model = $this->createModel();
            /** @var ActiveRecord[] $models */
            $models = array_merge(['model' => $model], $this->getRelatedModels());
            if (($attributes = Yii::$app->request->post('attributes')) !== null) {
                $data = array_merge($models, ['attributes' => $attributes]);
                return $this->renderAjax('update_modal', $data);
            }
            else {
                $attributes = [];
                foreach ($this->getBatchEditableAttributes($models) as $key => $modelAttributes) {
                    foreach ($modelAttributes as $attribute)
                        $attributes[$key][] = new ModelAttribute([
                            'name' => $attribute,
                            'label' => $models[$key]->getAttributeLabel($attribute)
                        ]);
                }
                return $this->renderAjax('_batch_settings_form', ['attributes' => $attributes]);
            }
        }
    }

    /**
     * Deletes an existing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $this->setFlashMessages('delete');
        if (($result = $this->afterDelete()) !== null) {
            return $result;
        }
    }

    /**
     * Deletes multiple models.
     * @return mixed
     */
    public function actionBatchDelete()
    {
        if (($ids = Yii::$app->request->post('ids')) !== null) {
            $models = $this->findModels($ids);
            foreach ($models as $model) {
                $model->delete();
            }
            $this->setFlashMessages('batchDelete');
        }
        return $this->redirect(['index']);
    }

    /**
     * @return ActiveRecord
     */
    protected function createModel()
    {
        $modelClass = $this->getModelClass();
        return new $modelClass;
    }

//    /**
//     * @param ActiveRecord|null $model
//     * @param array|null $data
//     * @return ActiveRecord[]
//     */
//    protected function getRelatedModels($model = null, $data = null)
//    {
//        return [];
//    }

    /**
     * @param ActiveRecord|null $model
     * @return ActiveRecord[]
     */
    protected function getRelatedModels($model = null)
    {
        return [];
    }

    /**
     * @param Model $model
     * @param array $data the data array.
     * @param string $scope
     * @return boolean whether the model is successfully populated with some data.
     */
    protected function loadModel($model, $data, $scope = null)
    {
        return $model->load($data, $scope);
    }

    /**
     * @param Model[] $models
     * @param array $data the data array.
     * @param string|array $scope
     * @return boolean whether the models is successfully populated with some data.
     */
    protected function loadModels($models, $data, $scope = null)
    {
        $loaded = true;
        foreach ($models as $key => $model) {
            $scope = is_array($scope) && isset($scope[$key]) ? $scope[$key] : $scope;
            if (is_array($model)) {
                if (!Model::loadMultiple($model, $data, $scope)) {
                    $loaded = false;
                }
            } elseif (!$model->load($data, $scope)) {
                $loaded = false;
            }
        }
        return $loaded;
    }

    /**
     * @param Model[] $models
     * @return boolean whether the validation is successful without any error.
     */
    protected function validateModels($models)
    {
        $valid = true;
        foreach ($models as $model) {
            if (is_array($model)) {
                if (!Model::validateMultiple($model)) {
                    $valid = false;
                }
            } elseif (!$model->validate()) {
                $valid = false;
            }
        }
        return $valid;
    }

    /**
     * @param Model[] $models
     */
    protected function onValidateError($models)
    {
    }

    /**
     * @param ActiveRecord[] $models
     * @param boolean $create whether this method called while creation a record.
     * @param array $parameters request parameters
     * @return boolean whether the saving succeeds.
     */
    protected function saveModels($models, $create = false, $parameters = [])
    {
        $saved = true;
        foreach ($models as $model) {
            if (is_array($model)) {
                foreach ($model as $item) {
                    /** @var ActiveRecord $item */
                    if (!$item->save(false)) {
                        $saved = false;
                    }
                }
            } elseif (!$model->save(false)) {
                $saved = false;
            }
        }
        return $saved;
    }

    /**
     * @param ActiveRecord[] $models
     * @param boolean $create whether this method called while creation a record.
     * @return mixed
     */
    protected function afterSave($models, $create = false)
    {
        if (($result = $this->afterSaveRedirect($models['model'], $create)) !== null) {
            return $result;
        }
    }

    /**
     * @param ActiveRecord[] $models
     * @param boolean $create whether this method called while creation a record.
     * @return mixed
     */
    protected function onSaveError($models, $create = false)
    {
        if (($result = $this->afterSaveRedirect($models['model'], $create, true)) !== null) {
            return $result;
        }
    }

    /**
     * @return mixed
     */
    protected function afterDelete()
    {
        return $this->redirect(['index']);
    }

    /**
     * @param $action
     * @param bool $error whether this method called after action with errors.
     * @param array $params
     * @return mixed
     */
    protected function setFlashMessages($action, $error = false, $params = [])
    {
        $type = $error ? 'danger' : 'success';
        $message = $type . ucfirst($action);
        if (isset($this->{$message})) {
            $message = $this->{$message};
            if (isset($params['placeholders']))
                $message = str_replace(array_map(function($key){return '{' . $key . '}';},
                    array_keys($params['placeholders'])), $params['placeholders'], $message);
            Yii::$app->session->setFlash($type, $message);
        }
    }

    /**
     * @param ActiveRecord $model
     * @param boolean $create whether this method called while creation a record.
     * @param bool $error whether this method called after saving with errors.
     * @return mixed
     */
    protected function afterSaveRedirect($model, $create = false, $error = false)
    {
        $code = Yii::$app->request->isPjax ? 200 : 302;
        if ($error) {
            return Yii::$app->getResponse()->redirect(Yii::$app->getRequest()->getUrl(), $code);
        }
        else {
            return $this->redirect(['update', 'id' => $model->id], $code);
        }
    }

    /**
     * @param integer|array $id
     * @param array $with
     * @throws NotFoundHttpException
     * @return ActiveRecord|ActiveRecord[]
     */
    protected function findModels($id, $with = [])
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->getModelClass();
        $query = $modelClass::find()->andWhere(['id' => $id]);
        if ($with) {
            $query->with($with);
        }
        if (is_array($id)) {
            $model = $query->all();
        } else {
            $model = $query->one();
        }

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested model does not exist.');
        }
    }

    /**
     * @param integer $id
     * @param array $with
     * @throws NotFoundHttpException
     * @return ActiveRecord
     */
    protected function findModel($id, $with = [])
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->getModelClass();
        $query = $modelClass::find()->andWhere(['id' => $id]);
        if ($with) {
            $query->with($with);
        }
        $model = $query->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested model does not exist.');
        }
    }

    /**
     * @param array $ids
     * @param array $data
     * @return ActiveRecord[]
     */
    protected function findModelsToBatchUpdate($ids, array $data)
    {
        return $this->findModels($ids);
    }

    protected function getBatchEditableAttributes(array $models)
    {
        return [];
    }

    /**
     * @param string $method name
     * @param array $args method's argument list
     * @return array associative array of method parameters
     */
    protected function getMethodParameters($method, $args)
    {
        $parameters = [];
        $method = new \ReflectionMethod($this, $method);
        foreach ($method->getParameters() as $key => $param) {
            if (array_key_exists($key, $args)) {
                $name = $param->getName();
                $parameters[$name] = $args[$key];
            }
        }

        return $parameters;
    }
}
