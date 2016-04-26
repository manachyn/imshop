<?php

namespace im\tree\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;
use Yii;

class JsTree extends Widget
{
    /**
     * @var array the HTML attributes for the container tag of the widget.
     */
    public $options = [];

    /**
     * @var array the options for the underlying jsTree plugin.
     */
    public $clientOptions = [];

    /**
     * @var array the event handlers for the underlying jsTree widget.
     */
    public $clientEvents = [];

    /**
     * @var array the options for api.
     */
    public $apiOptions = [];

    /**
     * @var string id of tree details widget
     */
    public $treeDetails;

    /**
     * @var string id of confirmation modal
     */
    public $confirmationModal;

    public $items = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initOptions();
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        echo Html::beginTag($tag, $options);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScript();
        if ($this->items) {
            echo $this->renderTree($this->items);
        }
        echo Html::endTag(isset($this->options['tag']) ? $this->options['tag'] : 'div');
    }

    public function renderTree($items, &$level = 0)
    {
        $level++;
        $tree = Html::beginTag('ul');
        foreach ($items as $item) {
            $data = call_user_func($this->data, $item, $this);
            $text = ArrayHelper::remove($data, 'text');
            $id = ArrayHelper::remove($data, 'id');
            $children = ArrayHelper::remove($data, 'children');
            if ($children) {
                $text .= $this->renderTree($children, $level);
            }
            $tree .= Html::tag('li', $text, ['id' => $id, 'data-jstree' => json_encode($data)]);
        }
        $tree .= Html::endTag('ul');
        $level--;
        return $tree;
    }

    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript()
    {
        $id = $this->options['id'];
        $view = $this->getView();
        JsTreeAsset::register($view);
        $options = Json::encode($this->clientOptions);
        $apiOptions = Json::encode($this->apiOptions);
        $view->registerJs("$('#$id').jsTreeApi($apiOptions)");
        $view->registerJs("$('#$id').jstree($options)");
        $this->registerClientEvents();
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        $this->apiOptions = array_merge([
            'rootsUrl' => Url::to(['roots']),
            'childrenUrl' => Url::to(['descendants', 'level' => 1]),
            'createUrl' => Url::to(['create-node']),
            'editUrl' => Url::to(['update', 'id' => '{id}']),
            'updateUrl' => Url::to(['update', 'id' => '{id}']),
            'deleteUrl' => Url::to(['delete', 'id' => '{id}']),
            'moveUrl' => Url::to(['move', 'id' => '{id}']),
            'searchUrl' => Url::to(['search']),
            'treeDetails' => $this->treeDetails,
            'searchInput' => '.tree-search',
            'searchableAttributes' => false
        ], $this->apiOptions);
        $this->clientOptions = ArrayHelper::merge([
            'core' => [
                'animation' => 0,
                'data' => [
                    'url' => new JsExpression(
                        "function (node) {
                            var url;
                            if (node.id === '#') {
                                url = '" . $this->apiOptions['rootsUrl'] . "';
                            } else {
                                url = '" . $this->apiOptions['childrenUrl'] . "';
                                url = url.replace('{id}', node.id);
                            }
                            return url;
                        }"
                    ),
//                    'data' => new JsExpression(
//                        "function (node) {
//                            return {'id': node.id};
//                        }"
//                    )
                ]
            ],
            'search' => [
                'ajax' => [
                    'url' => $this->apiOptions['searchUrl'],
                    'method' => 'POST',
                    'processData' => false
                ]
            ],
            'plugins' => ['contextmenu', 'dnd', 'search', /*'state',*/ 'types'],
            'contextmenu' => [
                'items' => [
                    'edit' => [
                        'label' => Yii::t('app', 'Edit'),
                        'icon' => 'fa fa-pencil',
                        'action' => new JsExpression(
                                "function (data) {
                                        var inst = $.jstree.reference(data.reference),
                                            obj = inst.get_node(data.reference);
                                        inst.trigger('edit_node', {node: obj});
                                }"
                            ),
                    ],
                    'create' => [
                        'label' => Yii::t('app', 'Create'),
                        'icon' => 'fa fa-plus-circle',
                        'action' => new JsExpression(
                                "function (data) {
                                    var inst = $.jstree.reference(data.reference),
                                        obj = inst.get_node(data.reference);
                                    inst.create_node(obj, {}, 'last', function (new_node) {
                                        setTimeout(function () { inst.edit(new_node); },0);
                                    });
                                }"
                            ),
                    ],
                    'delete' => [
                        'label' => Yii::t('app', 'Delete'),
                        'icon' => 'fa fa-trash-o',
                        'action' => new JsExpression(
                                "function (data) {
                                    var inst = $.jstree.reference(data.reference),
                                        obj = inst.get_node(data.reference);
                                    if(inst.is_selected(obj)) {
                                        inst.delete_node(inst.get_selected());
                                    }
                                    else {
                                        inst.delete_node(obj);
                                    }
                                }"
                            ),
                    ],
                    'rename' => [
                        'label' => Yii::t('app', 'Rename'),
                        'icon' => 'fa fa-font',
                        'action' => new JsExpression(
                                "function (data) {
                                    var inst = $.jstree.reference(data.reference),
                                        obj = inst.get_node(data.reference);
                                    inst.edit(obj);
                                }"
                            ),
                    ],
                ],
            ],
        ], $this->clientOptions);
    }

    /**
     * Registers JS event handlers that are listed in [[clientEvents]].
     */
    protected function registerClientEvents()
    {
        if (!empty($this->clientEvents)) {
            $id = $this->options['id'];
            $js = [];
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = ".on('$event', $handler)";
            }
            $js = "$('#$id')" . implode("\n", $js). ";";
            $this->getView()->registerJs($js);
        }
    }
} 