<?php

namespace im\search\tests\codeception\common\fixtures;

use yii\test\ActiveFixture;

/**
 * ProductAttribute fixture
 */
class ProductAttributeFixture extends ActiveFixture
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'im\catalog\models\ProductAttribute';

    /**
     * @inheritdoc
     */
    public function unload()
    {
        $this->resetTable();
        parent::unload();
    }
}

