<?php

namespace im\seo\components;

use im\base\interfaces\ModelBehaviorInterface;
use im\forms\components\FieldSet;
use im\seo\models\Meta;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use Yii;
use yii\validators\Validator;

/**
 * Seo behavior
 *
 * @property Meta $metaRelation
 */
class SeoBehavior extends Behavior implements ModelBehaviorInterface
{
    /**
     * @var ActiveRecord
     */
    public $owner;

    /**
     * @var string
     */
    public $metaClass = 'app\modules\seo\models\Meta';

    /**
     * @var string
     */
    public $ownerType;

    /**
     * @var Meta
     */
    private $_model;

    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        parent::attach($owner);
        if ($this->ownerType !== false && $this->ownerType === null) {
            $this->ownerType = $owner::className();
        }
        $validators = $this->owner->getValidators();
        $validator = Validator::createValidator('\app\modules\base\validators\RelationValidator', $this->owner, ['meta']);
        $validators->append($validator);
    }

    public function events()
    {
        return [
            FieldSet::EVENT_BEFORE_RENDER => 'beforeFormRender',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetaRelation()
    {
        $query = $this->owner->hasOne($this->metaClass, ['entity_id' => 'id']);
        if ($this->ownerType) {
            $query->where(['entity_type' => $this->ownerType]);
        }
        return $query;
    }

    /**
     * @return Meta
     */
    public function getMeta()
    {
        $meta = $this->owner->metaRelation;
        if ($meta === null) {
            $meta = Yii::createObject($this->metaClass);
        }
        return $meta;
    }

    /**
     * @param FieldSetEvent $event
     */
    public function beforeFormRender($event)
    {
        $fieldSet = $event->fieldSet;
        $metaForm = Yii::$app->getView()->render('@im/seo/backend/views/meta/_form', [
            'form' => $fieldSet->getForm(),
            'model' => $this->_model ? $this->_model : $this->getMeta()
        ]);
        $metaTab = new Tab('meta', Module::t('meta', 'Meta information'), [
            new ContentBlock('metaForm', $metaForm)
        ]);
        $fieldSet->addItem($metaTab, 'tabs');
    }

    /**
     * Handles afterInsert event of the owner.
     */
    public function afterInsert()
    {
        if ($this->_model) {
            if ($this->ownerType) {
                $this->_model->entity_type = $this->ownerType;
            }
            $this->owner->link('metaRelation', $this->_model);
        }
    }

    /**
     * Handles afterUpdate event of the owner.
     */
    public function afterUpdate()
    {
        if ($this->_model) {
            $this->_model->save(false);
        }
    }

    /**
     * @inheritdoc
     */
    public function load($data)
    {
        $this->_model = $this->getMeta();
        $this->owner->populateRelation('metaRelation', $this->_model);
        return $this->_model->load($data);
    }
}