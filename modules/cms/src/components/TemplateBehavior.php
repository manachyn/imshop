<?php

namespace im\cms\components;

use im\base\interfaces\ModelBehaviorInterface;
use im\cms\models\Template;
use im\forms\components\FieldSet;
use im\forms\components\FieldSetEvent;
use im\forms\components\Tab;
use im\seo\Module;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\validators\Validator;

/**
 * Template behavior
 *
 * @property ActiveRecord $owner
 * @property Template $template
 */
class TemplateBehavior extends Behavior implements ModelBehaviorInterface
{
    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        parent::attach($owner);
        $validators = $this->owner->getValidators();
        $validator = Validator::createValidator('safe', $this->owner, 'template_id');
        $validators->append($validator);
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            FieldSet::EVENT_BEFORE_RENDER => 'beforeFormRender'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->owner->hasOne(Template::className(), ['id' => 'template_id']);
    }

    /**
     * @param FieldSetEvent $event
     */
    public function beforeFormRender($event)
    {
        $fieldSet = $event->fieldSet;
        $templateTab = new Tab('template', Module::t('template', 'Template'), [
            $fieldSet->getForm()->field($fieldSet->getModel(), 'template_id')->dropDownList(
                ArrayHelper::map(Template::find()->asArray()->all(), 'id', 'name'),
                ['prompt' => '']
            )->label(Module::t('template', 'Template'))
        ]);
        $fieldSet->addItem($templateTab, 'tabs');
    }

    /**
     * @inheritdoc
     */
    public function load($data)
    {
        return true;
    }
}