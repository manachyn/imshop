<?php

namespace im\cms\widgets;

use im\cms\models\Gallery;
use im\cms\models\widgets\ModelWidget;
use im\cms\Module;

/**
 * Class GalleryWidget
 * @package im\cms\widgets
 * @property bool $display_title
 * @property integer $display_count
 * @property string $list_url
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class GalleryWidget extends ModelWidget
{
    const TYPE = 'gallery';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['display_title', 'display_count'], 'integer'],
            [['list_url'], 'safe']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('widget', 'ID'),
            'model_id' => Module::t('widget', 'Gallery'),
            'display_title' => Module::t('widget', 'Display galley name'),
            'display_count' => Module::t('widget', 'Items count to display'),
            'list_url' => Module::t('widget', 'Gallery page url'),
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
        return '@im/cms/backend/views/widgets/gallery-widget/_form';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('gallery', [
            'widget' => $this,
            'gallery' => $this->loadGallery()
        ]);
    }

    /**
     * @return Gallery
     */
    private function loadGallery()
    {
        return Gallery::findOne($this->model_id);
    }
}