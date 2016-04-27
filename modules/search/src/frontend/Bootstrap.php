<?php

namespace im\search\frontend;

use im\base\routing\ModuleRulesTrait;
use im\base\types\EntityType;
use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package im\search\frontend
 */
class Bootstrap extends \im\search\Bootstrap implements BootstrapInterface
{
    use ModuleRulesTrait;

    /**
     * Module routing.
     *
     * @return array
     */
    public function getRules()
    {
        return [
            [
                'class' => 'yii\rest\UrlRule',
                'prefix' => 'api/v1',
                'extraPatterns' => [
                    'GET,HEAD suggest/<text:.+>' => 'suggest'
                ],
                'controller' => ['search' => 'search/rest/search']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        parent::bootstrap($app);
        $this->addRules($app);
    }
}
