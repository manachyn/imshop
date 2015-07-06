<?php

namespace im\cms\models;

use im\cms\Module;

/**
 * This is the model class for table "{{%banner_widgets}}".
 */
class BannerWidget extends Widget
{
    const TYPE = 'banner';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['banner_id'], 'safe']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('page', 'ID'),
            'banner_id' => Module::t('page', 'Banner')
        ];
    }

    /**
     * @inheritdoc
     */
    public function getCMSTitle()
    {
        return Module::t('page', 'Banner widget');
    }

    /**
     * @inheritdoc
     */
    public function getCMSDescription()
    {
        return Module::t('page', 'Widget for displaying banners on the page.');
    }

    /**
     * @inheritdoc
     */
    public function getEditView()
    {
        return '';
        //return '@app/modules/cms/backend/views/widget/banner-widget/_form';
    }
}