<?php

namespace im\elfinder\widgets;

use im\elfinder\ElFinderWidgetAsset;
use yii\base\Widget;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

class ElFinder extends Widget
{
    /**
     * @var array the HTML attributes for the widget container tag.
     */
    public $options = [];

    /**
     * @var array the options for the underlying elFinder widget
     */
    public $clientOptions = [];

    public $language;

    public $filter;

    public $callbackFunction;

    public $path;// work with PathController

    public $containerOptions = [];

    public $frameOptions = [];

    public $controller = 'elfinder';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initOptions();
        echo Html::beginTag('div', $this->options) . "\n";
    }

    /**
     * Get file manager options for CKEditor.
     *
     * @param string|array $managerRoute
     * @param array $options
     * @return array
     */
    public static function getCKEditorOptions($managerRoute, $options = [])
    {
        $managerRoute = (array) $managerRoute;

        return ArrayHelper::merge([
            'filebrowserBrowseUrl' => Url::to($managerRoute),
            'filebrowserImageBrowseUrl' => Url::to(ArrayHelper::merge($managerRoute, ['filter'=>'image'])),
            'filebrowserFlashBrowseUrl' => Url::to(ArrayHelper::merge($managerRoute, ['filter'=>'flash'])),
        ], $options);
    }

    /**
     * Get file manager options for TinyMCE.
     *
     * @param string|array $managerRoute
     * @param array $options
     * @return array
     */
    public static function getTinyMCEOptions($managerRoute, $options = [])
    {
        $managerUrl = Url::to($managerRoute);

        return ArrayHelper::merge([
            'file_browser_callback' => new JsExpression("
                function elFinderBrowser (field_name, url, type, win) {
                  tinymce.activeEditor.windowManager.open({
                    file: '$managerUrl',
                    title: 'elFinder 2.0',
                    width: 900,
                    height: 450,
                    resizable: 'yes'
                  }, {
                    setUrl: function (url) {
                      win.document.getElementById(field_name).value = url;
                    }
                  });
                  return false;
                }
            ")
        ], $options);
    }

//    public static function getManagerUrl($controller, $params = [])
//    {
//        $params[0] = '/'.$controller."/manager";
//        return Yii::$app->urlManager->createUrl($params);
//    }
//
//    public static function ckeditorOptions($controller, $options = []){
//        if(is_array($controller)){
//            $id = $controller[0];
//            unset($controller[0]);
//            $params = $controller;
//        }else{
//            $id = $controller;
//            $params = [];
//        }
//        return ArrayHelper::merge([
//            'filebrowserBrowseUrl' => self::getManagerUrl($id, $params),
//            'filebrowserImageBrowseUrl' => self::getManagerUrl($id, ArrayHelper::merge($params, ['filter'=>'image'])),
//            'filebrowserFlashBrowseUrl' => self::getManagerUrl($id, ArrayHelper::merge($params, ['filter'=>'flash'])),
//        ], $options);
//    }
//    public function init()
//    {
//        if(empty($this->language))
//            $this->language = self::getSupportedLanguage(Yii::$app->language);
//        $managerOptions = [];
//        if(!empty($this->filter))
//            $managerOptions['filter'] = $this->filter;
//        if(!empty($this->callbackFunction))
//            $managerOptions['callback'] = $this->id;
//        if(!empty($this->language))
//            $managerOptions['lang'] = $this->language;
//        if(!empty($this->path))
//            $managerOptions['path'] = $this->path;
//        $this->frameOptions['src'] = $this->getManagerUrl($this->controller, $managerOptions);
//        if(!isset($this->frameOptions['style'])){
//            $this->frameOptions['style'] = "width: 100%; height: 100%; border: 0;";
//        }
//    }
//    static function getSupportedLanguage($language)
//    {
//        $supportedLanguages = array('bg', 'jp', 'sk', 'cs', 'ko', 'th', 'de', 'lv', 'tr', 'el', 'nl', 'uk',
//            'es', 'no', 'vi', 'fr', 'pl', 'zh_CN', 'hr', 'pt_BR', 'zh_TW', 'hu', 'ro', 'it', 'ru', 'en');
//        if(!in_array($language, $supportedLanguages)){
//            if (strpos($language, '-')){
//                $language = str_replace('-', '_', $language);
//                if(!in_array($language, $supportedLanguages)) {
//                    $language = substr($language, 0, strpos($language, '_'));
//                    if (!in_array($language, $supportedLanguages))
//                        $language = false;
//                }
//            } else {
//                $language = false;
//            }
//        }
//        return $language;
//    }
//
//    public function run()
//    {
//        $container = 'div';
//        if(isset($this->containerOptions['tag'])){
//            $container = $this->containerOptions['tag'];
//            unset($this->containerOptions['tag']);
//        }
//        echo Html::tag($container, Html::tag('iframe','', $this->frameOptions), $this->containerOptions);
//        if(!empty($this->callbackFunction)){
//            AssetsCallBack::register($this->getView());
//            $this->getView()->registerJs("mihaildev.elFinder.register(".Json::encode($this->id).",".Json::encode($this->callbackFunction).");");
//        }
//    }

    public function run()
    {
        echo "\n" . Html::endTag('div');

        $this->registerClientScript();
    }

    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript()
    {
        $id = $this->options['id'];
        $view = $this->getView();
        ElFinderWidgetAsset::register($view);
        $options = Json::encode($this->clientOptions);
        $view->registerJs("$('#$id').elfinder($options);");
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

        $this->clientOptions = ArrayHelper::merge([
            'url' => Url::to(['/elfinder/connector'])
        ], $this->clientOptions);
    }
} 