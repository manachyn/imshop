<?php

namespace im\search\tests\codeception\common\fixtures;

use yii\test\ActiveFixture;

/**
 * ProductsCategories fixture
 */
class ProductsCategoriesFixture extends ActiveFixture
{
    /**
     * @inheritdoc
     */
    public $tableName = '{{%products_categories}}';

    /**
     * @inheritdoc
     */
    public $depends = [
        'im\search\tests\codeception\common\fixtures\ProductFixture',
        'im\search\tests\codeception\common\fixtures\ProductCategoryFixture'
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
