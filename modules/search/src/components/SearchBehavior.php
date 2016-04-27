<?php

namespace im\search\components;

use im\base\interfaces\ModelBehaviorInterface;
use im\forms\components\FieldSet;
use im\forms\components\FieldSetEvent;
use im\forms\components\Tab;
use im\search\components\query\facet\FacetInterface;
use im\search\models\FacetSet;
use im\seo\Module;
use im\tree\models\Tree;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\validators\Validator;

/**
 * Class SearchBehavior
 * @property ActiveRecord $owner
 * @package im\seo\components
 */
class SearchBehavior extends Behavior implements ModelBehaviorInterface
{
    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        parent::attach($owner);
        $validators = $this->owner->getValidators();
        $validator = Validator::createValidator('safe', $this->owner, 'facet_set_id');
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
    public function getFacetSet()
    {
        return $this->owner->hasOne(FacetSet::className(), ['id' => 'facet_set_id']);
    }


    /**
     * @return FacetInterface[]
     */
    public function getFacets()
    {
        $model = $this->owner;
        if ($facetSet = $this->getFacetSet()->one()) {
            return $facetSet->facets;
        } elseif ($model instanceof Tree && ($parent = $model->parents(1)->one())) {
            return $parent->getFacets();
        }

        return [];
    }

    /**
     * @param FieldSetEvent $event
     */
    public function beforeFormRender($event)
    {
        $fieldSet = $event->fieldSet;
        $searchTab = new Tab('search', Module::t('search', 'Search settings'), [
            $fieldSet->getForm()->field($fieldSet->getModel(), 'facet_set_id')->dropDownList(
                ArrayHelper::map(FacetSet::find()->asArray()->all(), 'id', 'name'),
                ['prompt' => '']
            )->label(Module::t('search', 'Facets set'))
        ]);
        $fieldSet->addItem($searchTab, 'tabs');
    }

    /**
     * @inheritdoc
     */
    public function load($data)
    {
        return true;
    }
}