<?php

namespace im\search\tests\codeception\common\fixtures;

use yii\test\ActiveFixture;

/**
 * ProductCategory fixture
 */
class ProductCategoryFixture extends ActiveFixture
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'im\catalog\models\ProductCategory';

    /**
     * @inheritdoc
     */
    public function unload()
    {
        $this->resetTable();
        parent::unload();
    }
}

