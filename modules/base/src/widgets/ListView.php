<?php

namespace im\base\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class ListView extends \yii\widgets\ListView
{
    const MODE_LIST = 'list';
    const MODE_GRID = 'grid';

    /**
     * @var array the options for jQuery widget.
     */
    public $clientOptions = [];

    /**
     * @var array the event handlers for the underlying jQuery widget.
     */
    public $clientEvents = [];

    /**
     * @var string
     */
    public $mode = self::MODE_LIST;

    /**
     * @var array the HTML attributes for the items container.
     */
    public $itemsContainerOptions = [];

    /**
     * @var string
     */
    public $itemClass;

    /**
     * @var array the HTML attributes for the container of the rendering result of each data model.
     */
    public $itemContainerOptions = [];

    /**
     * @var string|callable the name of the view for rendering container fpr each data item, or a callback.
     */
    public $itemContainerView = '@im/base/widgets/views/list_view_item';

    /**
     * @var string|callable the name of the view for rendering container fpr each data item, or a callback.
     */
    public $toolbarView = '@im/base/widgets/views/list_view_toolbar';

    /**
     * @var array additional parameters to be passed to [[itemContainerView]] when it is being rendered.
     * This property is used only when [[itemContainerView]] is a string representing a view name.
     */
    public $containerViewParams = [];

    /**
     * @var \yii\widgets\ActiveForm|\im\forms\components\DynamicActiveForm
     */
    public $form;

    /**
     * @var string
     */
    public $fieldsNamePrefix;

    /**
     * @var bool whether the list items should be sortable.
     */
    public $sortable = false;

    /**
     * @var string
     */
    public $addLabel;

    /**
     * @inheritdoc
     */
    public $layout = "{toolbar}\n{summary}\n{items}\n{pager}\n{temp}";

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initOptions();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        parent::run();
        $id = $this->options['id'];
        ListViewAsset::register($this->getView());
        $this->registerClientOptions($id);
        $this->registerClientEvents($id);
    }

    /**
     * @inheritdoc
     */
    public function renderSection($name)
    {
        switch ($name) {
            case '{summary}':
                return $this->renderSummary();
            case '{items}':
                return $this->renderItems();
            case '{pager}':
                return $this->renderPager();
            case '{sorter}':
                return $this->renderSorter();
            case '{toolbar}':
                return $this->renderToolbar();
            case '{temp}':
                return $this->renderTempContainer();
            default:
                return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function renderItems()
    {
        $models = $this->dataProvider->getModels();
        $keys = $this->dataProvider->getKeys();
        $rows = [];
        foreach (array_values($models) as $index => $model) {
            $item = $this->renderItem($model, $keys[$index], $index);
            $rows[] = $this->renderItemContainer($model, $keys[$index], $index, $item);
        }

        return $this->renderItemsContainer(implode($this->separator, $rows));
    }

    /**
     * @inheritdoc
     */
    public function renderItem($model, $key, $index)
    {
        if ($this->itemView === null) {
            $content = $key;
        } elseif (is_string($this->itemView)) {
            $content = $this->getView()->render($this->itemView, ArrayHelper::merge([
                'model' => $model,
                'key' => $key,
                'index' => $index,
                'widget' => $this,
                'fieldConfig' => ['tabularIndex' => $index]
            ], $this->viewParams));
        } else {
            $content = call_user_func($this->itemView, $model, $key, $index, $this);
        }
        $options = $this->itemOptions;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        if ($tag !== false) {
            $options['data-key'] = is_array($key) ? json_encode($key, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : (string) $key;

            return Html::tag($tag, $content, $options);
        } else {
            return $content;
        }
    }

    /**
     * Renders items container.
     * @param string $itemsContent
     * @return string
     */
    public function renderItemsContainer($itemsContent)
    {
        $options = $this->itemsContainerOptions;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        if ($tag !== false) {
            return Html::tag($tag, $itemsContent, $options);
        } else {
            return $itemsContent;
        }
    }

    /**
     * Renders a single data model container.
     * @param mixed $model the data model to be rendered
     * @param mixed $key the key value associated with the data model
     * @param integer $index the zero-based index of the data model in the model array returned by [[dataProvider]].
     * @param string $itemContent
     * @return string the rendering result
     */
    public function renderItemContainer($model, $key, $index, $itemContent)
    {
        if ($this->itemContainerView) {
            if (is_string($this->itemContainerView)) {
                $itemContent = $this->getView()->render($this->itemContainerView, ArrayHelper::merge([
                    'model' => $model,
                    'key' => $key,
                    'index' => $index,
                    'widget' => $this,
                    'itemContent' => $itemContent,
                    'fieldConfig' => ['tabularIndex' => $index]
                ], $this->containerViewParams));
            } else {
                $itemContent = call_user_func($this->itemView, $model, $key, $index, $this, $itemContent);
            }
//            $options = $this->itemContainerOptions;
//            $tag = ArrayHelper::remove($options, 'tag', 'div');
//            if ($tag !== false) {
//                $options['data-key'] = is_array($key) ? json_encode($key, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : (string)$key;
//
//                return Html::tag($tag, $itemContent, $options);
//            } else {
//                return $itemContent;
//            }
        }

        return $itemContent;
    }

    /**
     * Renders the toolbar.
     * @return string the rendering result
     */
    public function renderToolbar()
    {
        return $this->getView()->render($this->toolbarView, ['widget' => $this]);
    }

    /**
     * Renders temp container.
     * @return string the rendering result
     */
    public function renderTempContainer()
    {
        return Html::tag('div', '', ['data-cont' => 'list-temp']);
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        $this->options = array_merge([
            'class' => $this->mode === self::MODE_LIST ? 'list-view' : 'row list-view'
        ], $this->options);
        $this->itemsContainerOptions = array_merge([
            'class' => $this->mode === self::MODE_LIST ? 'list-view-list' : 'container-fluid row list-view-list',
            'data-cont' => 'list'
        ], $this->itemsContainerOptions);
        $this->itemsContainerOptions['class'] .= ' list-view-mode-' . $this->mode;
        $this->itemContainerOptions = array_merge([
            'class' => $this->mode === self::MODE_LIST ? 'list-view-item' : 'col-sm-6 col-md-4 col-lg-2 list-view-item'
        ], $this->itemContainerOptions);
        $this->clientOptions = array_merge([
            'sortable' => $this->sortable,
            'addUrl' => Url::to(['/base/list-view/add']),
            'form' => $this->form,
            'itemClass' => $this->itemClass,
            'itemView' => $this->itemView,
            'viewParams' => array_merge($this->viewParams, ['widget' => [
                'sortable' => $this->sortable,
                'itemContainerOptions' => $this->itemContainerOptions
            ]]),
            'itemContainerView' => $this->itemContainerView,
            // Sortable
            'items' => '.list-view-item',
            'placeholder' => $this->itemContainerOptions['class'] . ' sortable-placeholder',
            'forcePlaceholderSize' => true
        ], $this->clientOptions);

        if ($this->mode === self::MODE_GRID) {
            $this->clientEvents = array_merge([
                'sortstart' => 'function (event, ui) { ui.placeholder.height(ui.placeholder.height() - 2); }',
                //'sortstart' => 'function (event, ui) { ui.placeholder.css("visibility", "visible"); ui.placeholder.height(ui.placeholder.height() - 2); }',
                //'sortstop' => 'function (event, ui) { ui.item.toggleClass("sortable-placeholder"); }'
            ], $this->clientEvents);
        } else {
            $this->clientOptions['axis'] = 'y';
        }
    }

    /**
     * Registers a specific jQuery widget options
     * @param string $id the ID of the widget
     */
    protected function registerClientOptions($id)
    {
        if ($this->clientOptions !== false) {
            $options = empty($this->clientOptions) ? '' : Json::htmlEncode($this->clientOptions);
            $this->getView()->registerJs("jQuery('#$id').sortable($options);");
            $this->getView()->registerJs("jQuery('#$id').listView($options);");
        }
    }

    /**
     * Registers a specific jQuery widget events
     * @param string $id the ID of the widget
     */
    protected function registerClientEvents($id)
    {
        if (!empty($this->clientEvents)) {
            $js = [];
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = "jQuery('#$id').on('$event', $handler);";
            }
            $this->getView()->registerJs(implode("\n", $js));
        }
    }
}