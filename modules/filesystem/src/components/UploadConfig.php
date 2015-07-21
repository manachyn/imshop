<?php

namespace im\filesystem\components;

use Closure;
use creocoder\flysystem\Filesystem;
use League\Flysystem\AdapterInterface;
use yii\base\Configurable;
use Yii;
use yii\web\UploadedFile;

class UploadConfig implements Configurable
{
    /**
     * @var string|callable path or alias to the directory in which to save files
     * or anonymous function returns directory path
     */
    public $path = '@webroot/uploads';

    /**
     * @var Filesystem|string the filesystem object or the application component ID of the filesystem object.
     */
    public $filesystem;

    /**
     * @var string|callable path or alias to the directory in which to save files
     * or anonymous function returns directory path
     */
    public $url = '/uploads';

    /**
     * @var string|callable new file name (template)
     * Uses original name by default
     */
    public $fileName = '{file.basename}';

    public $multiple = false;

    public $visibility = AdapterInterface::VISIBILITY_PUBLIC;

    public function __construct($config = [])
    {
        if (!empty($config)) {
            Yii::configure($this, $config);
        }
        if (is_string($this->filesystem)) {
            $filesystem = Yii::$app->get('filesystem', false);
            if ($filesystem instanceof FilesystemComponent) {
                $this->filesystem = $filesystem->get($this->filesystem);
            }
        }
    }

    public function getFilePath(UploadedFile $file)
    {
        //$path = Yii::getAlias($this->path) . DIRECTORY_SEPARATOR . ;
    }

    public function resolvePath($model)
    {
        $path = Yii::getAlias($this->path instanceof Closure ? call_user_func($this->path, $model, $this) : $this->path);
        return preg_replace_callback('|{(.*?)}|', function ($matches) use ($model) {
            $value = $this->evaluateExpression($matches[1], ['model' => $model]);
            return $value !== null ? $value : $matches[0];
        }, $path);
    }

    public function resolveFileName($fileName, $model, $fileIndex = 1)
    {
        $name = $this->fileName instanceof Closure ? call_user_func($this->fileName, $fileName, $model, $this) : $this->fileName;
        $file = pathinfo($fileName);
        $file['index'] = $fileIndex;
        return preg_replace_callback('|{(.*?)}|', function ($matches) use ($file, $model) {
            $value = $this->evaluateExpression($matches[1], ['file' => $file, 'model' => $model]);
            return $value !== null ? $value : $matches[0];
        }, $name);
    }

    public function resolveFilePath($fileName, $model, $fileIndex = 1)
    {
        return rtrim($this->resolvePath($model), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->resolveFileName($fileName, $model, $fileIndex);
    }

//    public function resolveFileName($fileName, $model)
//    {
//        $file = pathinfo($fileName);
//        return preg_replace_callback('|{(.*?)}|', function ($matches) use ($file, $model) {
//            $value = $this->evaluateExpression($matches[1], ['file' => $file, 'model' => $model]);
//            return $value ? $value : $matches[0];
//        }, $this->fileName);
//    }

    private function evaluateExpression($expression, $parameters)
    {
        $parts = explode('.', $expression);
        $value = !empty($parameters[$parts[0]]) ? $parameters[array_shift($parts)] : null;
        if ($parts) {
            if (is_object($value)) {
                $value = $value->{array_shift($parts)};
            } elseif (is_array($value)) {
                $value = $value[array_shift($parts)];
            }
            if ($parts) {
                array_unshift($parts, 'obj');
                $expression = implode('.', $parts);
                $value = $this->evaluateExpression($expression, ['obj' => $value]);
            }
        }
        return $value;
    }
}