<?php

namespace im\config\tests\codeception\common\unit;

use im\config\components\DBConfigProvider;

/**
 * Class DBConfigProviderTest
 * @package im\config\tests\codeception\common\unit
 */
class DBConfigProviderTest extends DbTestCase
{
    public function testSet()
    {
        $provider = $this->getProvider();
        $provider->set('key', 'value');
        $this->assertEquals('value', $provider->get('key'));
    }

    public function testSetWithContext()
    {
        $provider = $this->getProvider();
        $provider->set('context key', 'context value', 'context');
        $this->assertEquals('context value', $provider->get('context key', 'context'));
    }

    public function testUpdate()
    {
        $provider = $this->getProvider();
        $provider->set('key', 'value');
        $this->assertEquals('value', $provider->get('key'));
        $provider->set('key', 'new value');
        $this->assertEquals('new value', $provider->get('key'));
    }

    public function testUpdateWithContext()
    {
        $provider = $this->getProvider();
        $provider->set('context key', 'context value', 'context');
        $this->assertEquals('context value', $provider->get('context key', 'context'));
        $provider->set('context key', 'new context value', 'context');
        $this->assertEquals('new context value', $provider->get('context key', 'context'));
    }

    public function testGetWithContextAndWithout()
    {
        $provider = $this->getProvider();
        $provider->set('key1', 'value1', 'context');
        $provider->set('key2', 'value2');
        $this->assertEquals('value1', $provider->get('key1', 'context'));
        $this->assertEquals('value2', $provider->get('key2', 'context'));

        $provider->set('key3', 'value4');
        $provider->set('key3', 'value5', 'context');
        $this->assertEquals('value4', $provider->get('key3'));
        $this->assertEquals('value5', $provider->get('key3', 'context'));
    }

    public function testGetMultiple()
    {
        $provider = $this->getProvider();
        $provider->set('key.subKey1', 'value1');
        $provider->set('key.subKey2', 'value2');
        $this->assertEquals(['key.subKey1' => 'value1', 'key.subKey2' => 'value2'], $provider->get('key.*'));
    }

    public function testGetMultipleWithContextAndWithout()
    {
        $provider = $this->getProvider();
        $provider->set('key.subKey1', 'value1');
        $provider->set('key.subKey1', 'value2', 'context');
        $provider->set('key.subKey2', 'value3');

        $this->assertEquals(['key.subKey1' => 'value1', 'key.subKey2' => 'value3'], $provider->get('key.*'));
        $this->assertEquals(['key.subKey1' => 'value2', 'key.subKey2' => 'value3'], $provider->get('key.*', 'context'));
    }

    public function testRemove()
    {
        $provider = $this->getProvider();
        $provider->set('key', 'value1');
        $provider->set('key', 'value2', 'context');
        $provider->set('key', 'value3', 'context2');
        $provider->remove('key', 'context');
        $this->assertEquals('value1', $provider->get('key', 'context'));
        $provider->remove('key');
        $this->assertNull($provider->get('key'));
        $this->assertNull($provider->get('key', 'context2'));
    }

    /**
     * @return DBConfigProvider
     */
    protected function getProvider()
    {
        return new DBConfigProvider();
    }
}

