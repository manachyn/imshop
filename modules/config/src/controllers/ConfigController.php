<?php

namespace im\config\controllers;

use im\base\controllers\BackendController;
use im\config\components\ConfigInterface;
use im\config\components\ConfigManager;
use im\config\components\ConfigNotFoundException;
use im\config\components\ConfigProviderInterface;
use im\config\components\ConfigurableInterface;
use im\config\components\EditableConfigInterface;
use im\config\models\Config;
use im\config\Module;
use yii\base\DynamicModel;
use yii\base\InvalidParamException;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * Class ConfigController
 * @package im\config\controllers
 */
class ConfigController extends BackendController
{
    /**
     * Edits config.
     *
     * @param string $key
     * @return mixed
     */
    public function actionUpdate($key)
    {
        $model = $this->findConfig($key);

        if (!$model instanceof EditableConfigInterface) {
            throw new InvalidParamException("$key config is not editable");
        }

        /** @var ConfigManager $configManager */
        $configManager = Yii::$app->get('configManager');
        $model = $configManager->loadConfig($model);

        if ($configManager->saveConfig($model, Yii::$app->request->post())) {
            Yii::$app->session->setFlash('success', Module::t('module', 'Config has been successfully saved.'));
            return $this->redirect(['update', 'key' => $model->getKey()]);
        }

        return $this->render('update', ['model' => $model]);
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

    /**
     * Finds the config on its config key value.

     * @param string $key
     * @return ConfigInterface
     * @throws NotFoundHttpException
     */
    protected function findConfig($key)
    {
        /** @var ConfigManager $configManager */
        $configManager = Yii::$app->get('configManager');

        if ($config = $configManager->getConfig($key)) {
            return $config;
        } else {
            throw new NotFoundHttpException('The requested config does not exist.');
        }
    }
}

