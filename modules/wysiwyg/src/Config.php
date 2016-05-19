<?php

namespace im\wysiwyg;

use im\config\components\EditableConfigInterface;
use im\config\models\Config as BaseConfig;

/**
 * Class WysiwygConfig
 * @package im\wysiwyg
 */
class Config extends BaseConfig implements EditableConfigInterface
{
    const KEY = 'wysiwyg';

    const EDITOR_TINYMCE = 'tinymce';
    const EDITOR_CKEDITOR = 'ckeditor';

    const PRESET_BASIC = 'basic';
    const PRESET_STANDARD = 'standard';
    const PRESET_FULL = 'full';

    /**
     * @var string
     */
    public $editor = self::EDITOR_TINYMCE;

    /**
     * @var string
     */
    public $preset = self::PRESET_STANDARD;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['editor', 'preset'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'editor' => Module::t('module', 'Editor'),
            'preset' => Module::t('module', 'Preset')
        ];
    }

    /**
     * @inheritdoc
     */
    public function getKey()
    {
        return self::KEY;
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return Module::t('module', 'Wysiwyg');
    }

    /**
     * @inheritdoc
     */
    public function getEditView()
    {
        return '@im/wysiwyg/backend/views/config/_form';
    }

    /**
     * @inheritdoc
     */
    public function getUserSpecificOptions()
    {
        return ['editor'];
    }

    /**
     * Get array of available editors.
     *
     * @return array
     */
    public function getEditorsList()
    {
        return [
            self::EDITOR_TINYMCE => 'TinyMCE',
            self::EDITOR_CKEDITOR => 'CKEditor'
        ];
    }

    /**
     * Get array of available presets.
     *
     * @return array
     */
    public function getPresetsList()
    {
        return [
            self::PRESET_BASIC => Module::t('module', 'Basic'),
            self::PRESET_STANDARD => Module::t('module', 'Standard'),
            self::PRESET_FULL => Module::t('module', 'Full')
        ];
    }
}

