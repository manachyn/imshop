<?php

namespace im\webpack\commands;

use Yii;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\FileHelper;
use yii\web\AssetBundle;

/**
 * Class WebpackController
 * @package im\webpack\commands
 */
class WebpackController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'build';

    /**
     * @var array
     */
    public $entryPoints = [];

    /**
     * @var array|\yii\web\AssetManager
     */
    private $_assetManager = [];

    /**
     * Build entry points.
     *
     * @param string $configFile
     */
    public function actionBuild($configFile)
    {
        $this->loadConfiguration($configFile);
        $entryPoints = $this->loadEntryPoints($this->entryPoints);
        foreach ($entryPoints as $name => $entryPoint) {
            $this->stdout("Build entry point bundle '{$entryPoint->className()}' => ");
            $file = $this->buildBundle($entryPoint, $entryPoint->js);
            $this->stdout($file . "\n");
        }
    }

    /**
     * Returns the asset manager instance.
     *
     * @throws \yii\console\Exception
     * @return \yii\web\AssetManager
     */
    public function getAssetManager()
    {
        if (!is_object($this->_assetManager)) {
            $options = $this->_assetManager;
            if (!isset($options['class'])) {
                $options['class'] = 'yii\\web\\AssetManager';
            }
            if (!isset($options['basePath'])) {
                throw new Exception("Please specify 'basePath' for the 'assetManager' option.");
            }
            if (!isset($options['baseUrl'])) {
                throw new Exception("Please specify 'baseUrl' for the 'assetManager' option.");
            }
            $this->_assetManager = Yii::createObject($options);
        }

        return $this->_assetManager;
    }

    /**
     * Sets asset manager instance or configuration.
     * @param \yii\web\AssetManager|array $assetManager asset manager instance or its array configuration.
     * @throws \yii\console\Exception on invalid argument type.
     */
    public function setAssetManager($assetManager)
    {
        if (is_scalar($assetManager)) {
            throw new Exception('"' . get_class($this) . '::assetManager" should be either object or array - "' . gettype($assetManager) . '" given.');
        }
        $this->_assetManager = $assetManager;
    }

    /**
     * Applies configuration from the given file to self instance.
     * @param string $configFile configuration file name.
     * @throws \yii\console\Exception on failure.
     */
    protected function loadConfiguration($configFile)
    {
        $this->stdout("Loading configuration from '{$configFile}'...\n");
        foreach (require($configFile) as $name => $value) {
            if (property_exists($this, $name) || $this->canSetProperty($name)) {
                $this->$name = $value;
            } else {
                throw new Exception("Unknown configuration option: $name");
            }
        }

        $this->getAssetManager(); // check if asset manager configuration is correct
    }

    /**
     * @param array $entryPoints
     * @return \yii\web\AssetBundle[]
     * @throws Exception
     */
    protected function loadEntryPoints($entryPoints)
    {
        foreach ($entryPoints as $name => $bundle) {
            if (!isset($bundle['class'])) {
                $bundle['class'] = $bundle;
            }
            $entryPoints[$name] = Yii::createObject($bundle);
        }

        return $entryPoints;
    }

    /**
     * @param \yii\web\AssetBundle $bundle
     * @param string $buildFile
     * @param array $types
     * @return array
     * @throws Exception
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    protected function buildBundle(AssetBundle $bundle, $buildFile = 'bundle.js', $types = ['css', 'js'])
    {
        //$alias = str_replace('\\', '/', $bundle::className());
        $files = [];
        $requiredFiles = [];
        foreach ($types as $type) {
            if (is_array($bundle->$type)) {
                foreach ($bundle->$type as $file) {
                    if (is_array($file)) {
                        $files[] = $bundle->sourcePath . '/' . $file[0];
                    } else {
                        $files[] = $bundle->sourcePath . '/' . $file;
                    }
                }
            }
        }
        if ($bundle->depends) {
            $am = $this->getAssetManager();
            foreach ($bundle->depends as $name) {
                $requiredFiles[] = $this->buildBundle($am->getBundle($name), 'bundle.js', $types);
            }
        } elseif (count($files) == 1) {
            return $files[0];
        }
        $files = array_merge($requiredFiles, $files);
        foreach ($files as $key => $file) {
            if (pathinfo($file, PATHINFO_FILENAME) == 'jquery') {
                $files[$key] = "var $ = window.jQuery = window.$ = require('$file');";
            } else {
                $files[$key] = "require('$file');";
            }
        }
        FileHelper::createDirectory($bundle->basePath, $this->getAssetManager()->dirMode);
        $entryFile = $bundle->basePath . '/' . $buildFile;
        file_put_contents($entryFile, implode("\n", $files));

        return $entryFile;
    }
} 