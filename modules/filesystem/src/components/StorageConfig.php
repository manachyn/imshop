<?php

namespace im\filesystem\components;

use Closure;
use creocoder\flysystem\Filesystem;
use League\Flysystem\AdapterInterface;
use yii\base\Object;
use yii\db\ActiveQuery;
use yii\web\UploadedFile;
use Yii;

class StorageConfig extends Object
{
    /**
     * @var string filesystem name.
     */
    public $filesystem;

    /**
     * @var string|callable path or alias to the directory in which to save files
     * or anonymous function returns directory path
     */
    public $path = '@webroot/uploads';

    /**
     * @var string|callable path or alias to the directory in which to save files
     * or anonymous function returns directory path
     */
    public $url = '/uploads';

    /**
     * @var string|callable new file name (template).
     * Uses original name by default
     */
    public $fileName = '{file.basename}';

    /**
     * @var bool
     */
    public $multiple = false;

    /**
     * @var string
     */
    public $relation;

    /**
     * @var bool
     */
    public $deleteOnUnlink = false;

    /**
     * @var array
     */
    public $extraColumns = [];

    /**
     * @var array
     */
    public $events = [];

    /**
     * @var bool whether to update file names after owner was saved first time.
     * It is used in cases if the file name should contain owner primary key.
     */
    public $updateAfterCreation = false;

    public $visibility = AdapterInterface::VISIBILITY_PUBLIC;

    public function __set($name, $value)
    {
        if (strncmp($name, 'on ', 3) === 0) {
            $this->events[trim(substr($name, 3))] = $value;
            return;
        } else {
            parent::__set($name, $value);
        }
    }

    public function resolvePath($model)
    {
        $path = Yii::getAlias($this->path instanceof Closure ? call_user_func($this->path, $model, $this) : $this->path);
        return preg_replace_callback('|{(.*?)}|', function ($matches) use ($model) {
            $value = $this->evaluateExpression($matches[1], ['model' => $model]);
            return $value !== null ? $value : $matches[0];
        }, $path);
    }

    public function resolveFileName($fileName, $model)
    {
        $name = $this->fileName instanceof Closure ? call_user_func($this->fileName, $fileName, $model, $this) : $this->fileName;
        $file = pathinfo($fileName);
        return preg_replace_callback('|{(.*?)}|', function ($matches) use ($file, $model) {
            $value = $this->evaluateExpression($matches[1], ['file' => $file, 'model' => $model]);
            return $value !== null ? $value : $matches[0];
        }, $name);
    }

    public function resolveFilePath($fileName, $model)
    {
        return rtrim($this->resolvePath($model), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->resolveFileName($fileName, $model);
    }

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