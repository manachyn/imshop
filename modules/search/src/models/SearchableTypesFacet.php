<?php

namespace im\search\models;

/**
 * Class SearchableTypesFacet
 * @package im\catalog\models
 */
class SearchableTypesFacet extends TermsFacet
{
    const TYPE = 'searchable_types_facet';

    /**
     * @var string
     */
    public $name = 'type';

    /**
     * @var string
     */
    public $index_name = 'type';

    /**
     * @inheritdoc
     */
    public function isMultivalue()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function isDisplayValuesWithoutResults()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function isDisplaySelectedValues()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getEditView()
    {
        return '@im/search/backend/views/searchable-types-facet/_form.php';
    }
}