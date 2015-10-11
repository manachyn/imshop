<?php

namespace im\elfinder;

use creocoder\flysystem\Filesystem;
use im\filesystem\components\FilesystemComponent;
use yii\helpers\Inflector;
use yii\web\Controller;
use Yii;

class ElFinderController extends Controller
{
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
} 