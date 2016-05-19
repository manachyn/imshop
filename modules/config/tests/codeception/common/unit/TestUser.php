<?php

namespace im\config\tests\codeception\common\unit;

use yii\web\IdentityInterface;

/**
 * Class TestUser
 * @package im\config\tests\codeception\common\unit
 */
class TestUser implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return new TestUser();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return new TestUser();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return 1;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return 'authKey';
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return true;
    }
}
