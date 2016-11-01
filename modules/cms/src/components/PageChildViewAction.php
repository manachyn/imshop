<?php

namespace im\cms\components;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class PageChildViewAction
 * @package im\cms\components
 */
class PageChildViewAction extends PageViewAction
{
    /**
     * Displays page child.
     * If the page is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $path
     * @param string $childPath
     * @throws \yii\web\NotFoundHttpException
     * @return mixed
     */
    public function run($path = 'index', $childPath = null)
    {
        if ($childPath) {
            $model = $this->findModel($path);
            if (!$model) {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
            if ($model instanceof ModelsListPageInterface) {
                $childClass = $model->getModelClass();
                if (is_subclass_of($childClass, PageInterface::class)) {
                    /** @var PageInterface $childClass */
                    if ($route = $childClass::getViewRoute()) {
                        return Yii::$app->runAction($route, ['path' => $childPath, 'parentPage' => $model]);
                    }
                }
            }
        }

        return parent::run($path);
    }
}
