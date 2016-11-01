<?php

namespace im\blog\models;

use im\blog\Module;
use im\cms\components\ModelsListPageInterface;
use im\cms\models\Page;
use im\forms\components\FieldSet;
use im\forms\components\FieldSetEvent;
use yii\helpers\ArrayHelper;

/**
 * Class NewsListPage
 * @package im\blog\models
 * @property int $category_id
 */
class NewsListPage extends Page implements ModelsListPageInterface
{
    const TYPE = 'news_list_page';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->on(FieldSet::EVENT_BEFORE_RENDER, [$this, 'beforeFormRender']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['category_id'], 'integer']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'category_id' => Module::t('news-list-page', 'News category')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getModelClass()
    {
        return News::class;
    }

    /**
     * @inheritdoc
     */
    public static function getViewRoute()
    {
        return 'blog/news-list-page/view';
    }

    /**
     * @param FieldSetEvent $event
     */
    public function beforeFormRender($event)
    {
        $fieldSet = $event->fieldSet;
        $form = $fieldSet->getForm();
        $field = $form->field($this, 'category_id')->dropDownList(
            ArrayHelper::map(NewsCategory::find()->asArray()->orderBy('name')->all(), 'id', 'name'),
            ['prompt' => '']
        );
        $fieldSet->addItem($field, 'tabs.main');
    }
}
