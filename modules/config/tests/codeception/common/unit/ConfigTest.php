<?php

namespace im\config\tests\codeception\common\unit;

use im\config\components\Config;
use im\config\components\ConfigManager;
use im\config\components\DBConfigProvider;
use Yii;
use yii\web\User;

/**
 * Class ConfigTest
 * @package im\config\tests\codeception\common\unit
 */
class ConfigTest extends DbTestCase
{
    public function testSet()
    {
        $config = $this->getConfig();
        $config->set('test-config.option1', 'value');
        $this->assertEquals('value', $config->get('test-config.option1'));
    }

    public function testSetUserSpecific()
    {
        $config = $this->getConfig(true);
        $config->set('test-config.option2', 'value');
        $this->assertEquals('value', $config->get('test-config.option2'));
    }

    public function testGetMultiple()
    {
        $config = $this->getConfig();
        $config->set('test-config.option1', 'value1');
        $config->set('test-config.option2', 'value2');
        $this->assertEquals(['test-config.option1' => 'value1', 'test-config.option2' => 'value2'], $config->get('test-config.*'));
    }

    public function testGetMultipleUserSpecific()
    {
        $config = $this->getConfig(true);
        $config->set('test-config.option1', 'value1');
        $config->set('test-config.option2', 'value2');
        $config->user->setIdentity(null);
        $this->assertEquals(['test-config.option1' => 'value1'], $config->get('test-config.*'));
    }

    public function testGetDefault()
    {
        $config = $this->getConfig();
        $this->assertEquals(1, $config->get('test-config.option1'));
        $config->set('test-config.option1', 2);
        $this->assertEquals(2, $config->get('test-config.option1'));
    }

    public function testGetMultipleDefault()
    {
        $config = $this->getConfig();
        $this->assertEquals(['test-config.option1' => 1], $config->get('test-config.*'));
    }

    /**
     * @param bool $loginUser
     * @return Config
     */
    protected function getConfig($loginUser = false)
    {
        $configManager = new ConfigManager();
        $configManager->registerConfig(new TestConfig());
        /** @var User $mockedUser */
        $user = Yii::createObject(['class' => User::class, 'identityClass' => TestUser::class]);
        if ($loginUser) {
            $user->setIdentity(new TestUser());
        }

        return new Config(['configManager' => $configManager, 'user' => $user]);
    }
}

