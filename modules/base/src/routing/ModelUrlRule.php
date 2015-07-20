<?php

namespace im\base\routing;

use yii\db\ActiveRecordInterface;
use yii\web\UrlRule;

class ModelUrlRule extends UrlRule
{
    /**
     * @var string model class
     */
    public $modelClass;

    /**
     * @var string url attribute in the model
     */
    public $urlAttribute = 'slug';

    /**
     * @var string url parameter in the route
     */
    public $urlParam = 'url';

    public function parseRequest($manager, $request)
    {
        $result = parent::parseRequest($manager, $request);
        if ($result) {
            $params = $result[1];
            if (isset($params[$this->urlParam])) {
                /* @var $modelClass ActiveRecordInterface */
                $modelClass = $this->modelClass;
                //$model = $modelClass::findOne([$this->urlAttribute => $params[$this->urlParam]]);
                //return (bool) $model;
                return $result;
            }
        }

        return false;
    }
}