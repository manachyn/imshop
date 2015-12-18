<?php

namespace im\cms;

use im\base\types\EntityType;
use im\cms\models\Page;
use yii\base\Application;
use yii\base\BootstrapInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\caching\Cache;
use yii\web\UrlRule;
use yii\web\UrlRuleInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * Module routing.
     *
     * @var array
     */
    public $rules = [
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
            'controller' => ['menu-items' => 'cms/rest/menu-item']
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

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        //$layoutManager->registerOwner('im\cms\models\Page', 'page');
        //$layoutManager->registerConfigurableComponent($this);
        $this->addRules($app);
        $this->registerTranslations($app);
        $this->registerDefinitions();
        $this->registerEntityTypes();
        $this->registerPageTypes();
        $this->registerWidgets($app);
    }

    /**
     * Adds module rules.
     *
     * @param Application $app
     */
    public function addRules($app)
    {
        $urlManager = $app->getUrlManager();
        if ($urlManager->cache instanceof Cache) {
            $cacheKey = __CLASS__;
            $hash = md5(json_encode($this->rules));
            if (($data = $urlManager->cache->get($cacheKey)) !== false && isset($data[1]) && $data[1] === $hash) {
                $this->rules = $data[0];
            } else {
                $this->rules = $this->buildRules($this->rules, $urlManager);
                $urlManager->cache->set($cacheKey, [$this->rules, $hash]);
            }
        } else {
            $this->rules = $this->buildRules($this->rules, $urlManager);
        }
        $urlManager->addRules($this->rules, false);
    }

    /**
     * Register module translations.
     *
     * @param Application $app
     */
    public function registerTranslations($app)
    {
        $app->i18n->translations[Module::$messagesCategory . '/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@im/cms/messages',
            'fileMap' => [
                Module::$messagesCategory => 'module.php',
                Module::$messagesCategory . '/page' => 'page.php',
                Module::$messagesCategory . '/menu' => 'menu.php'
            ]
        ];
    }

    /**
     * Registers a class definitions in container.
     */
    public function registerDefinitions()
    {
        Yii::$container->set('im\cms\models\Page', [
            'as seo' => [
                'class' => 'im\seo\components\SeoBehavior',
                'metaClass' => 'im\cms\models\PageMeta',
                'ownerType' => false
            ],
            'as template' => [
                'class' => 'im\cms\components\TemplateBehavior'
            ]
        ]);
    }

    /**
     * Registers widgets.
     *
     * @param Application $app
     */
    public function registerWidgets($app)
    {
        $layoutManager = $app->get('layoutManager');
        $layoutManager->registerWidget('im\cms\models\widgets\ContentWidget');
        $layoutManager->registerWidget('im\cms\models\widgets\BannerWidget');
    }

    /**
     * Registers entity types.
     */
    public function registerEntityTypes()
    {
        /** @var \im\base\types\EntityTypesRegister $typesRegister */
        $typesRegister = Yii::$app->get('typesRegister');
        $typesRegister->registerEntityType(new EntityType('page_meta', 'im\cms\models\PageMeta'));
    }

    /**
     * Registers page types.
     */
    public function registerPageTypes()
    {
        /** @var \im\cms\components\Cms $cms */
        $cms = Yii::$app->get('cms');
        $cms->registerPageType(new EntityType('page', 'im\cms\models\Page', 'page', 'Static page'));
    }

    /**
     * Builds URL rule objects from the given rule declarations.
     * @param array $rules the rule declarations. Each array element represents a single rule declaration.
     * Please refer to [[rules]] for the acceptable rule formats.
     * @param \yii\web\UrlManager $urlManager
     * @return \yii\web\UrlRuleInterface[] the rule objects built from the given rule declarations
     * @throws InvalidConfigException if a rule declaration is invalid
     */
    protected function buildRules($rules, $urlManager)
    {
        $compiledRules = [];
        $verbs = 'GET|HEAD|POST|PUT|PATCH|DELETE|OPTIONS';
        foreach ($rules as $key => $rule) {
            if (is_string($rule)) {
                $rule = ['route' => $rule];
                if (preg_match("/^((?:($verbs),)*($verbs))\\s+(.*)$/", $key, $matches)) {
                    $rule['verb'] = explode(',', $matches[1]);
                    // rules that do not apply for GET requests should not be use to create urls
                    if (!in_array('GET', $rule['verb'])) {
                        $rule['mode'] = UrlRule::PARSING_ONLY;
                    }
                    $key = $matches[4];
                }
                $rule['pattern'] = $key;
            }
            if (is_array($rule)) {
                $rule = Yii::createObject(array_merge($urlManager->ruleConfig, $rule));
            }
            if (!$rule instanceof UrlRuleInterface) {
                throw new InvalidConfigException('URL rule class must implement UrlRuleInterface.');
            }
            $compiledRules[] = $rule;
        }

        return $compiledRules;
    }
}