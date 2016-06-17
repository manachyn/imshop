<?php

namespace im\search\tests\codeception\common\fixtures;

use yii\test\ActiveFixture;

/**
 * Product fixture
 */
class ProductFixture extends ActiveFixture
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'im\catalog\models\Product';

    /**
     * @inheritdoc
     */
    public function unload()
    {
        $this->resetTable();
        parent::unload();
    }

//    public function beforeLoad() {
//        parent::beforeLoad();
//        $this->db->createCommand()->setSql('SET FOREIGN_KEY_CHECKS = 0')->execute();
//    }
//
//    public function afterLoad() {
//        parent::afterLoad();
//        $this->db->createCommand()->setSql('SET FOREIGN_KEY_CHECKS = 1')->execute();
//    }
}

