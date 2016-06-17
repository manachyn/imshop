<?php

namespace im\cms\backend;

use im\base\routing\ModuleRulesTrait;

/**
 * Class Bootstrap
 * @package im\cms\backend
 */
class Bootstrap extends \im\cms\Bootstrap
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
                    'GET,HEAD roots' => 'roots',
                    'GET,HEAD leaves' => 'leaves',
                    'GET,HEAD {id}/children' => 'children',
                    'GET,HEAD {id}/descendants' => 'descendants',
                    'GET,HEAD {id}/leaves' => 'leaves',
                    'GET,HEAD {id}/ancestors' => 'ancestors',
                    'GET,HEAD {id}/parent' => 'parent',
                    'PUT,PATCH {id}/move' => 'move',
                    'POST search' => 'search'
                ],
                'controller' => ['menu-items' => 'cms/rest/menu-item', 'pages' => 'cms/rest/page']
            ],
            [
                'class' => 'yii\rest\UrlRule',
                'prefix' => 'api/v1',
                'extraPatterns' => [
                    'GET,HEAD {id}/items/roots' => 'roots',
                    'POST {id}/items/search' => 'search',
                ],
                'controller' => ['menus' => 'cms/rest/menu']
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
