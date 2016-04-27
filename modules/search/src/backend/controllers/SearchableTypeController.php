<?php

namespace im\search\backend\controllers;

use im\base\controllers\BackendController;
use im\search\models\IndexAttribute;
use Yii;
use im\search\models\Index;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;


class SearchableTypeController extends BackendController
{
    /**
     * List index attributes.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionSettings($id)
    {
        if ($data = Yii::$app->request->post('IndexAttribute', [])) {
            IndexAttribute::saveFromData($data);
        }
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('searchManager');
        $model = $this->findModel($id);
        $attributes = $searchManager->getIndexAttributes($model->type);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $attributes,
            'sort' => [
                'attributes' => ['name', 'label', 'index_name', 'indexable']
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
}
