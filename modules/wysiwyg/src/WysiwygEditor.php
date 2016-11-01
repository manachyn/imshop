<?php

namespace im\wysiwyg;

use im\ckeditor\CKEditor;
use im\elfinder\widgets\ElFinder;
use im\tinymce\TinyMCE;
use Yii;
use yii\widgets\InputWidget;

/**
 * Class WysiwygEditor
 * @package im\wysiwyg
 */
class WysiwygEditor extends InputWidget
{
    /**
     * @var string
     */
    public $editor = Config::EDITOR_CKEDITOR;

    /**
     * @var string
     */
    public $preset = Config::PRESET_FULL;

    /**
     * @var array
     */
    public $clientOptions = [];

    /**
     * @var string|array
     */
    public $fileManagerRoute;

    /**
     * @var InputWidget
     */
    private $_editor;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->fileManagerRoute) {
            $fileMangerConfig = $this->editor == Config::EDITOR_TINYMCE ? ElFinder::getTinyMCEOptions($this->fileManagerRoute)
                : ElFinder::getCKEditorOptions($this->fileManagerRoute);
            $this->clientOptions = array_merge($fileMangerConfig, $this->clientOptions);
        }
        if ($this->editor == Config::EDITOR_CKEDITOR) {
            $this->clientOptions['extraPlugins'] = 'richcombo,format,btgrid,widgetbootstrap,layoutmanager';
        }
        $this->_editor = Yii::createObject([
            'class' => $this->editor == Config::EDITOR_TINYMCE ? TinyMCE::class : CKEditor::class,
            'model' => $this->model,
            'attribute' => $this->attribute,
            'name' => $this->name,
            'value' => $this->value,
            'options' => $this->options,
            'clientOptions' => $this->clientOptions,
            'preset' => $this->preset
        ]);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->_editor->run();
    }
}

