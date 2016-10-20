<?php

namespace im\cms\widgets;

use im\cms\models\widgets\ModelWidget;
use im\cms\Module;

/**
 * Class MenuWidget
 * @package im\cms\widgets
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class MenuWidget extends ModelWidget
{
    const TYPE = 'menu';

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('widget', 'ID'),
            'model_id' => Module::t('widget', 'Menu')
        ];
    }

    /**
     * @inheritdoc
     */
    public function getCMSTitle()
    {
        return Module::t('widget', 'Menu widget');
    }

    /**
     * @inheritdoc
     */
    public function getCMSDescription()
    {
        return Module::t('widget', 'Widget for displaying menus on the page.');
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