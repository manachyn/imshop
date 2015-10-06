<?php

namespace im\search\widgets;

use im\cms\models\widgets\Widget;
use im\search\components\search\SearchResultContextInterface;
use im\search\Module;

/**
 * Facets widget class.
 *
 * @package im\search\widgets
 */
class FacetsWidget extends Widget
{
    const TYPE = 'facets';

    /**
     * @inheritdoc
     */
    public function getCMSTitle()
    {
        return Module::t('module', 'Facets widget');
    }

    /**
     * @inheritdoc
     */
    public function getCMSDescription()
    {
        return Module::t('module', 'Widget for displaying search facets');
    }

    /**
     * @inheritdoc
     */
    public function getEditView()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->context instanceof SearchResultContextInterface && ($result = $this->context->getResult()) && $facets = $result->getFacets()) {
            return $this->render('facets', ['facets' => $facets]);
        } else {
            return '';
        }
    }
}