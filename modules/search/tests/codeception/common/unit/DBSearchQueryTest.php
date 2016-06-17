<?php

namespace im\search\tests\codeception\common\unit;

use im\catalog\components\search\Product;
use im\search\components\query\QueryInterface;
use im\search\components\query\SearchQueryInterface;
use im\search\components\query\Term;
use im\search\components\searchable\SearchableInterface;
use im\search\components\service\db\Query;
use im\search\tests\codeception\common\fixtures\ProductCategoryFixture;
use im\search\tests\codeception\common\fixtures\ProductFixture;
use im\search\tests\codeception\common\fixtures\ProductsCategoriesFixture;
use im\search\tests\codeception\common\UnitTester;

/**
 * Class ConfigTest
 * @package im\config\tests\codeception\common\unit
 */
class DBSearchQueryTest extends DbTestCase
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function testSearchByAttribute()
    {
        $result = $this->getQuery(new Term('status', 1))->result()->getObjects();
        $this->assertCount(2, $result);
        $this->assertEquals($this->tester->getFixture('products')->getModel('product1'), $result[0]);
        $this->assertEquals($this->tester->getFixture('products')->getModel('product2'), $result[1]);
    }

    public function testSearchByRelationAttribute()
    {
        $result = $this->getQuery(new Term('categories.name', 'Notebooks'))->result()->getObjects();
        $this->assertCount(1, $result);
        $this->assertEquals($this->tester->getFixture('products')->getModel('product1'), $result[0]);
    }

//    public function testSearchByEavAttribute()
//    {
//        $result = $this->getQuery(new Term('hdd_attr', 320))->result()->getObjects();
//        $this->assertCount(1, $result);
//        $this->assertEquals($this->tester->getFixture('products')->getModel('product1'), $result[0]);
//    }

    /**
     * @param SearchQueryInterface $query
     * @return QueryInterface
     */
    protected function getQuery(SearchQueryInterface $query)
    {
        $searchableType = $this->getSearchableType();

        return new Query($searchableType->getClass(), [
            'searchableType' => $searchableType,
            'searchQuery' => $query
        ]);
    }

    /**
     * @return SearchableInterface
     */
    protected function getSearchableType()
    {
        return new Product([
            'type' => 'product',
            'modelClass' => 'im\catalog\models\Product'
        ]);
    }
}

