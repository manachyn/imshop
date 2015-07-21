<?php

namespace im\seo\components;

use yii\base\Component;
use yii\base\InvalidParamException;

class Seo extends Component
{
    public $seoModels = [];

//    /**
//     * @var array social meta types
//     */
//    public $metaTypes = [];

    /**
     * @var array social meta types
     */
    public $socialMetaTypes = [
        'open_graph' => 'im\seo\models\OpenGraph',
        'twitter_card' => 'im\seo\models\TwitterCard'
    ];

    public $metaTypeSocialMetaTypes = [];


    public function registerSocialMetaType($type, $class)
    {
        $this->socialMetaTypes[$type] = $class;
    }

//    public function getMetaClass($type)
//    {
//        if (isset($this->metaTypes[$type])) {
//            return $this->metaTypes[$type];
//        } elseif (class_exists($type)) {
//            return $type;
//        } else {
//            throw new InvalidParamException("Meta type '$type' is not registered");
//        }
//    }

    public function getSocialMetaClass($type)
    {
        if (isset($this->socialMetaTypes[$type])) {
            return $this->socialMetaTypes[$type];
        } elseif (class_exists($type)) {
            return $type;
        } else {
            throw new InvalidParamException("Social meta type '$type' is not registered");
        }
    }

    public function getSocialMetaTypes($metaType)
    {
        return isset($this->metaTypeSocialMetaTypes[$metaType]) ? $this->metaTypeSocialMetaTypes[$metaType]
            : array_keys($this->socialMetaTypes);
    }



    /**
     * @param string $modelClass
     * @return bool
     */
    public function isSeoModel($modelClass)
    {
        foreach ($this->seoModels as $model) {
            $class = (is_array($model) && !empty($model['class'])) ? $model['class'] : $model;
            if ($class == $modelClass || is_subclass_of($modelClass, $class)) {
                return true;
            }
        }

        return false;
    }
}