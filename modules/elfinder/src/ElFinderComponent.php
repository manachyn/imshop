<?php

namespace im\elfinder;

use yii\base\Component;

class ElFinderComponent extends Component
{
    /**
     * @var Root[]|array storages (root directories)
     */
    public $roots = [];

    /**
     * @var array filesystems, which use Flysystem.
     * You can set extra options.
     */
    public $filesystems = [];

    /**
     * @var array options for elFinder connector
     */
    public $options = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        foreach ($this->roots as $key => $root) {
            if (!$root instanceof Root) {
                $root['class'] = isset($root['class']) ? $root['class'] : LocalRoot::className();
                $this->roots[$key] = \Yii::createObject($root);
            }
        }
    }
} 