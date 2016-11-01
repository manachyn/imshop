<?php

namespace im\cms\frontend\forms;

use im\cms\models\FeedbackRequest;
use im\forms\components\FormInterface;
use Yii;

/**
 * Class FeedbackForm
 * @package im\cms\frontend\forms
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class FeedbackForm implements FormInterface
{
    const NAME = 'feedback';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @inheritdoc
     */
    public function render()
    {
        $model = Yii::createObject(FeedbackRequest::class);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

        }

        return Yii::$app->getView()->render('@im/cms/frontend/views/forms/_feedback', [
            'model' => $model,
            'form' => $this
        ]);
    }
}