<?php

namespace im\tree\controllers;

use im\base\components\ModelSerializer;
use im\base\controllers\CrudController;
use im\tree\models\Tree;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * CRUD Controller implements the base CRUD actions for tree model.
 */
abstract class CrudTreeController extends CrudController
{
    /**
     * @var string|array|ModelSerializer the configuration for creating the serializer that formats the response data for tree.
     */
    public $treeSerializer;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->treeSerializer && !$this->treeSerializer instanceof ModelSerializer) {
            $this->treeSerializer = Yii::createObject($this->treeSerializer);
        }
    }

    /**
     * @inheritdoc
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Creates a new tree node.
     * @param integer|null $parent
     * @param integer|null $position
     * @return mixed
     */
    public function actionCreateNode($parent = null, $position = null)
    {
        $model = $this->createModel();
        $models = array_merge(['model' => $model], $this->getRelatedModels());
        if ($this->loadModels($models, Yii::$app->request->post())) {
            if ($this->validateModels($models)) {
                if ($this->saveModels($models, true, $this->getMethodParameters('actionCreateNode', func_get_args()))) {
                    if (!Yii::$app->request->isAjax) {
                        $this->setFlashMessages('create', false, $models);
                    }
                    if (($result = $this->afterSave($models, true)) !== null) {
                        return $result;
                    }
                }
                else {
                    if (!Yii::$app->request->isAjax) {
                        $this->setFlashMessages('create', true, $models);
                    }
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
     * @inheritdoc
     */
    public function actionUpdate($id)
    {
        $model = $this->findModels($id);
        $models = array_merge(['model' => $model], $this->getRelatedModels($model));
        if ($this->loadModels($models, Yii::$app->request->post())) {
            if ($this->validateModels($models)) {
                if ($this->saveModels($models)) {
                    if (!Yii::$app->request->isAjax) {
                        $this->setFlashMessages('update', false, $models);
                    }
                    if (($result = $this->afterSave($models)) !== null) {
                        return $result;
                    }
                }
                else {
                    if (!Yii::$app->request->isAjax) {
                        $this->setFlashMessages('update', true, $models);
                    }
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

    /**
     * @inheritdoc
     */
    protected function afterSave($models, $create = false)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            //return $this->treeSerializer ? $this->treeSerializer->serialize($models['model']) : $models['model'];
            return $models['model'];
        } else {
            return parent::afterSave($models, $create);
        }
    }

    /**
     * @inheritdoc
     */
    protected function saveModels($models, $create = false, $parameters = [])
    {
        if ($create) {
            $node = ArrayHelper::remove($models, 'model');
            $parent = !empty($parameters['parent']) ? $parameters['parent'] : null;
            $position = !empty($parameters['position']) ? $parameters['position'] : null;
            return $this->saveNode($node, $parent, $position) && parent::saveModels($models);
        } else {
            return parent::saveModels($models);
        }
    }

    /**
     * @param Tree $node
     * @param Tree|null $parent
     * @param integer|null $position
     * @return boolean
     */
    protected function saveNode($node, $parent = null, $position = null)
    {
        if (!empty($parent)) {
            $parent = $this->findModel($parent);
            return $node->appendTo($parent, false);
        } else {
            return $node->makeRoot(false);
        }
    }
}
