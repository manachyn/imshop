<?php

namespace im\search\widgets;

use im\cms\models\widgets\Widget;
use im\search\components\SearchResultContextInterface;
use im\search\Module;

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
        if ($this->context instanceof SearchResultContextInterface && $result = $this->context->getResult()) {
            return $this->render('facets', ['facets' => $result->getFacets()]);
        } else {
            return '';
        }
    }
}