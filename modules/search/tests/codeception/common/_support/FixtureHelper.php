<?php

namespace im\search\tests\codeception\common\_support;

use Codeception\Module;
use im\search\tests\codeception\common\fixtures\ProductAttributeFixture;
use im\search\tests\codeception\common\fixtures\ProductAttributeValueFixture;
use im\search\tests\codeception\common\fixtures\ProductCategoryFixture;
use im\search\tests\codeception\common\fixtures\ProductFixture;
use im\search\tests\codeception\common\fixtures\ProductsCategoriesFixture;
use yii\test\FixtureTrait;
use yii\test\InitDbFixture;

/**
 * This helper is used to populate the database with needed fixtures before any tests are run.
 * In this example, the database is populated with the demo login user, which is used in acceptance
 * and functional tests.  All fixtures will be loaded before the suite is started and unloaded after it
 * completes.
 */
class FixtureHelper extends Module
{

    /**
     * Redeclare visibility because codeception includes all public methods that do not start with "_"
     * and are not excluded by module settings, in actor class.
     */
    use FixtureTrait {
        loadFixtures as public;
        fixtures as public;
        globalFixtures as public;
        createFixtures as public;
        unloadFixtures as protected;
        getFixtures as protected;
        getFixture as public;
    }

    /**
     * Method called before any suite tests run. Loads User fixture login user
     * to use in acceptance and functional tests.
     * @param array $settings
     */
    public function _beforeSuite($settings = [])
    {
        $this->loadFixtures();
    }

    /**
     * Method is called after all suite tests run
     */
    public function _afterSuite()
    {
        $this->unloadFixtures();
    }

    /**
     * @inheritdoc
     */
    public function globalFixtures()
    {
        return [
            InitDbFixture::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            'products' => [
                'class' => ProductFixture::className(),
                'dataFile' => '@tests/codeception/common/fixtures/data/product.php'
            ],
            'productCategories' => [
                'class' => ProductCategoryFixture::className(),
                'dataFile' => '@tests/codeception/common/fixtures/data/product_category.php'
            ],
            'productsCategories' => [
                'class' => ProductsCategoriesFixture::className(),
                'dataFile' => '@tests/codeception/common/fixtures/data/products_categories.php'
            ],
            'productAttributes' => [
                'class' => ProductAttributeFixture::className(),
                'dataFile' => '@tests/codeception/common/fixtures/data/product_attribute.php'
            ],
            'productAttributeValues' => [
                'class' => ProductAttributeValueFixture::className(),
                'dataFile' => '@tests/codeception/common/fixtures/data/product_attribute_value.php'
            ]
        ];
    }
}
