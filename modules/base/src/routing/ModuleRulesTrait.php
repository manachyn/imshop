<?php

namespace im\base\routing;

use Yii;
use yii\base\InvalidConfigException;
use yii\caching\Cache;
use yii\web\UrlRule;
use yii\web\UrlRuleInterface;

/**
 * Class ModuleRulesTrait
 * @package im\base\routing
 */
trait ModuleRulesTrait
{
    /**
     * @var array
     */
    private $_rules;

    /**
     * @return array
     */
    public function getRules()
    {
        return [];
    }

    /**
     * Adds module rules.
     *
     * @param \yii\base\Application $app
     */
    public function addRules($app)
    {
        $urlManager = $app->getUrlManager();
        if ($urlManager->cache instanceof Cache) {
            $cacheKey = __CLASS__;
            $hash = md5(json_encode($this->getRules()));
            if (($data = $urlManager->cache->get($cacheKey)) !== false && isset($data[1]) && $data[1] === $hash) {
                $this->_rules = $data[0];
            } else {
                $this->_rules = $this->buildRules($this->getRules(), $urlManager);
                $urlManager->cache->set($cacheKey, [$this->_rules, $hash]);
            }
        } else {
            $this->_rules = $this->buildRules($this->getRules(), $urlManager);
        }
        $urlManager->addRules($this->_rules, false);
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