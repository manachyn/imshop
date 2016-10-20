<?php

namespace im\cms\widgets;

use im\cms\models\widgets\ModelWidget;
use im\cms\Module;

/**
 * Class GalleryWidget
 * @package im\cms\widgets
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class GalleryWidget extends ModelWidget
{
    const TYPE = 'gallery';

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('widget', 'ID'),
            'model_id' => Module::t('widget', 'Gallery')
        ];
    }

    /**
     * @inheritdoc
     */
    public function getCMSTitle()
    {
        return Module::t('widget', 'Gallery widget');
    }

    /**
     * @inheritdoc
     */
    public function getCMSDescription()
    {
        return Module::t('widget', 'Widget for displaying galleries on the page.');
    }

    /**
     * @inheritdoc
     */
    public function getEditView()
    {
        return '';
        //return '@app/modules/cms/backend/views/widgets/banner-widget/_form';
    }
}