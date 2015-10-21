<?php

namespace im\cms\backend\controllers;

use im\base\controllers\BackendController;
use im\forms\components\DynamicActiveForm;
use Yii;

/**
 * LayoutController implements the actions to manage layouts.
 */
class LayoutController extends BackendController
{
    public function actionForm($id)
    {
        $layout = Yii::$app->get('layoutManager')->getLayout($id);
        $params = ['layout' => $layout];
        if ($form = Yii::$app->request->post('form')) {
            $params['form'] = new DynamicActiveForm(['config' => $form]);
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('edit', $params);
        }
        return $this->render('edit', $params);
    }

//    /**
//     * Updates an existing layout.
//     * @param string $id
//     * @return mixed
//     */
//    public function actionUpdate($id)
//    {
//        $layout = $this->findLayout($id);
//        if (Yii::$app->request->isPost) {
//            Yii::$app->layoutManager->saveWidgetAreas($layout->id, Yii::$app->request->post());
//            Yii::$app->session->setFlash('success', Module::t('module', 'Layout has been successfully saved.'));
//            return $this->redirect(['update', 'id' => $layout->id]);
//        }
//        return $this->render('update', [
//            'layout' => $layout
//        ]);
//    }
}
