<?php

namespace im\elfinder;

use creocoder\flysystem\Filesystem;
use im\filesystem\components\FilesystemComponent;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use yii\web\JsExpression;

/**
 * Class ElFinderController
 * @package im\elfinder
 */
class ElFinderController extends Controller
{
    /**
     * Connector action
     */
    public function actionConnector()
    {
        /** @var ElFinderComponent $elFinder */
        $elFinder = Yii::$app->get('elFinder');

        $roots = array_map(function ($root) {
            /** @var Root $root */
            return $root->getOptions();
        }, $elFinder->roots);

        /** @var FilesystemComponent $filesystem */
        $filesystem = Yii::$app->get('filesystem');

        foreach ($elFinder->filesystems as $key => $root) {
            if (is_string($root)) {
                $key = $root;
                $root = [];
            }
            $fs = $filesystem->get($key);
            if ($fs instanceof Filesystem) {
                $defaults = [
                    'driver' => 'Flysystem',
                    'filesystem' => $fs,
                    'alias' => Inflector::titleize($key),
//                    'glideURL' => 'http://imshop.loc/storage',
//                    'glideKey' => 'kmsTmQPdwm',
                ];
                $roots[] = array_merge($defaults, $root);
            }
        }

        $options = array(
            'locale' => '',
            'roots'  => $roots
        );
        $connector = new \elFinderConnector(new \elFinder($options));
        $connector->run();
    }

    public function actionManager()
    {
        return $this->renderFile(__DIR__ . '/views/manager.php', ['options' => $this->getManagerOptions(Yii::$app->request->get())]);
    }

    public function getManagerOptions(array $params)
    {
        $options = [
            'url'=> Url::toRoute('connector'),
            'customData' => [
                Yii::$app->request->csrfParam => Yii::$app->request->csrfToken
            ],
            'resizable' => false
        ];

        if (isset($params['CKEditor'])) {
            $options = ArrayHelper::merge($options, $this->getCKEditorManagerOptions($params));
        } elseif (isset($params['TinyMCE'])) {
            $options = ArrayHelper::merge($options, $this->getTinyMCEManagerOptions($params));
        }

        //$options['commandsOptions']['getfile']['onlyURL'] = true;

        if (isset($params['lang'])) {
            $options['lang'] = $params['lang'];
        }

        return $options;
    }

    public function getCKEditorManagerOptions(array $params)
    {
        $options = [];
        if (isset($params['CKEditor'])) {
            $funcNum = $params['CKEditorFuncNum'];
            $options['getFileCallback'] = new JsExpression("
                function (file) {
                    window.opener.CKEDITOR.tools.callFunction($funcNum, file.url);
                    window.close();
                }
            ");
            if (isset($params['langCode'])) {
                $options['lang'] = $params['langCode'];
            }
        }

        return $options;
    }

    public function getTinyMCEManagerOptions(array $params)
    {
        $options = [];
        if (isset($params['TinyMCE'])) {
            $options['getFileCallback'] = new JsExpression("
                function(file) {
                    // file.url - commandsOptions.getfile.onlyURL = false (default)
                    // file     - commandsOptions.getfile.onlyURL = true
                    // pass selected file path to TinyMCE
                    parent.tinymce.activeEditor.windowManager.getParams().setUrl(file.url);

                    // force the TinyMCE dialog to refresh and fill in the image dimensions
                    var t = parent.tinymce.activeEditor.windowManager.windows[0];
                    t.find('#src').fire('change');

                    // close popup window
                    parent.tinymce.activeEditor.windowManager.close();
                }
            ");
        }

        return $options;
    }
} 