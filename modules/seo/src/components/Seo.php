<?php

namespace im\seo\components;

use yii\base\Component;
use yii\base\InvalidParamException;

class Seo extends Component
{
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
}