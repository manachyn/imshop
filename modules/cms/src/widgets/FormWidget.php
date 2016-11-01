<?php

namespace im\cms\widgets;

use im\cms\models\widgets\ModelWidget;
use im\cms\Module;
use Yii;

/**
 * Class FormWidget
 * @package im\cms\widgets
 * @author Ivan Manachyn <manachyn@gmail.com>
 * @property string $name
 */
class FormWidget extends ModelWidget
{
    const TYPE = 'form';

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('widget', 'ID'),
            'model_id' => Module::t('widget', 'Form')
        ];
    }

    /**
     * @inheritdoc
     */
    public function getCMSTitle()
    {
        return Module::t('widget', 'Form widget');
    }

    /**
     * @inheritdoc
     */
    public function getCMSDescription()
    {
        return Module::t('widget', 'Widget for displaying forms on the page.');
    }

    /**
     * @inheritdoc
     */
    public function getEditView()
    {
        return '';
        //return '@app/modules/cms/backend/views/widgets/banner-widget/_form';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        /** @var \im\forms\components\FormsManager $formsManager */
        $formsManager = Yii::$app->get('formsManager');
        $form = $formsManager->getForm($this->name);

        return $form->render();
    }
}