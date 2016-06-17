<?php

namespace im\search\tests\codeception\common\fixtures;

use yii\test\ActiveFixture;

/**
 * ProductAttributeValue fixture
 */
class ProductAttributeValueFixture extends ActiveFixture
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'im\catalog\models\ProductAttributeValue';

    /**
     * @inheritdoc
     */
    public $depends = [
        'im\search\tests\codeception\common\fixtures\ProductAttributeFixture',
        'im\search\tests\codeception\common\fixtures\ProductFixture'
    ];

    /**
     * @inheritdoc
     */
    public function unload()
    {
        $this->resetTable();
        parent::unload();
    }
}

