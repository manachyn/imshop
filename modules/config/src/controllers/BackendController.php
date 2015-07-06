<?php

namespace im\config\controllers;

use app\modules\backend\components\Controller;
use im\config\components\ConfigManager;
use im\config\components\ConfigProviderInterface;
use im\config\components\ConfigurableInterface;
use yii\base\DynamicModel;
use yii\web\NotFoundHttpException;
use Yii;

class BackendController extends Controller
{
    /**
     * Edits component config.
     * @param string $component
     * @return mixed
     */
    public function actionConfig($component)
    {
        $component = $this->findComponent($component);
        /** @var ConfigManager $configManager */
        $configManager = Yii::$app->configManager;
        $model = $configManager->getComponentConfigModel($component);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $configManager->setComponentConfig($component, $model->getAttributes());
        }
        else {
            $componentConfig = $configManager->getComponentConfig($component);
            $model->setAttributes($componentConfig);
        }
        return $this->render('config', ['model' => $model, 'component' => $component]);
    }

    protected function collectRules($attributes) {

    }

    /**
     * Finds the component on its config key value.
     * If the component is not found, a 404 HTTP exception will be thrown.
     * @param string $key
     * @return ConfigurableInterface the loaded component
     * @throws NotFoundHttpException if the component cannot be found
     */
    protected function findComponent($key)
    {
        /** @var ConfigManager $configManager */
        $configManager = Yii::$app->configManager;

        if (($component = $configManager->getComponent($key)) !== null) {
            return $component;
        } else {
            throw new NotFoundHttpException('The requested component does not exist.');
        }
    }
}
