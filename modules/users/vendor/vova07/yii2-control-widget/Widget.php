<?php

namespace vova07\control;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\YiiAsset;

/**
 * Control widget
 */
class Widget extends \yii\base\Widget
{
    /**
     * @var string Widget title
     */
    public $title;

    /**
     * @var string Widget title url
     */
    public $url;

    /**
     * @var array Items array
     */
    public $items = [];

    /**
     * @var string Grid "id" selector
     */
    public $gridId;

    /**
     * @var integer Model ID
     */
    public $modelId;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->registerTranslations();
        $this->initDefaultItems();

        if ($this->items['batch-delete']['visible'] === true && $this->gridId === null) {
            throw new InvalidConfigException('The "gridId" property must be set if you you use "batch-delete" item.');
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScript();

        return $this->render('index');
    }

    /**
     * Set default control items.
     */
    public function initDefaultItems()
    {
        $items = [
            'create' => [
                'visible' => false,
                'url' => ['create'],
                'label' => '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('control', 'CREATE_BTN')
            ],
            'update' => [
                'visible' => false,
                'label' => '<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('control', 'UPDATE_BTN')
            ],
            'delete' => [
                'visible' => false,
                'label' => '<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('control', 'DELETE_BTN'),
                'linkOptions' => [
                    'id' => 'delete',
                    'data-confirm' => Yii::t('control', 'DELETE_CONFIRMATION'),
                    'data-method' => 'post'
                ]
            ],
            'batch-delete' => [
                'visible' => false,
                'url' => ['batch-delete'],
                'label' => '<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('control', 'BATCH_DELETE_BTN'),
                'linkOptions' => [
                    'id' => 'batch-delete'
                ]
            ],
            'cancel' => [
                'visible' => false,
                'url' => ['index'],
                'label' => '<span class="glyphicon glyphicon-remove"></span> ' . Yii::t('control', 'CANCEL_BTN')
            ],
            'back' => [
                'visible' => false,
                'url' => ['index'],
                'label' => '<span class="glyphicon glyphicon-chevron-left"></span> ' . Yii::t('control', 'BACK_BTN')
            ]
        ];

        if ($this->modelId !== null) {
            $items['delete']['visible'] = true;
            $items['delete']['url'] = ['delete', 'id' => $this->modelId];
            $items['update']['url'] = ['update', 'id' => $this->modelId];
        }
        if ($this->gridId !== null) {
            $items['batch-delete']['visible'] = true;
        }

        $this->items = ArrayHelper::merge($items, $this->items);
    }

    /**
     * Register widget translations.
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['control'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@vova07/control/messages',
            'forceTranslation' => true
        ];
    }

    /**
     * Register widget scripts
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        YiiAsset::register($view);

        if ($this->items['batch-delete']['visible'] === true) {
            $view->registerJs("jQuery(document).on('click', '#batch-delete', function (evt) {
                evt.preventDefault();
                var keys = jQuery('#" . $this->gridId . "').yiiGridView('getSelectedRows');
                if (keys == '') {
                    alert('" . Yii::t('control', 'BATCH_DELETE_NOT_SELECTED') . "');
                } else {
                    if (confirm('" . Yii::t('control', 'BATCH_DELETE_CONFIRMATION') . "')) {
                        jQuery.ajax({
                            type: 'POST',
                            url: jQuery(this).attr('href'),
                            data: { ids: keys}
                        });
                    }
                }
            });");
        }
    }
}
