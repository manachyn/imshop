<?php

namespace im\cms\widgets;

use im\cms\models\Banner;
use im\cms\models\widgets\ModelWidget;
use im\cms\Module;

/**
 * Class BannerWidget
 * @package im\cms\widgets
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class BannerWidget extends ModelWidget
{
    const TYPE = 'banner';

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('widget', 'ID'),
            'model_id' => Module::t('widget', 'Banner')
        ];
    }

    /**
     * @inheritdoc
     */
    public function getCMSTitle()
    {
        return Module::t('widget', 'Banner widget');
    }

    /**
     * @inheritdoc
     */
    public function getCMSDescription()
    {
        return Module::t('widget', 'Widget for displaying banners on the page.');
    }

    /**
     * @inheritdoc
     */
    public function getEditView()
    {
        return '@im/cms/backend/views/widgets/banner-widget/_form';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('banner', [
            'widget' => $this,
            'banner' => $this->loadBanner()
        ]);
    }

    /**
     * @return Banner
     */
    private function loadBanner()
    {
        return Banner::findOne($this->model_id);
    }
}
