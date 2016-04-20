<?php

namespace im\search\components\query\facet;
use im\search\components\query\SearchQueryInterface;

/**
 * Interface FacetInterface
 * @package im\search\components\query\facet
 */
interface FacetInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getField();

    /**
     * @return FacetValueInterface[]
     */
    public function getValues();

    /**
     * @param FacetValueInterface[] $values
     */
    public function setValues($values);

    /**
     * Whether facet can has multiple selected values.
     *
     * @return bool
     */
    public function isMultivalue();

    /**
     * Returns facet operator fo multivalue case.
     *
     * @return string
     */
    public function getOperator();

    /**
     * Sets facet filter.
     *
     * @param SearchQueryInterface $filter
     */
    public function setFilter(SearchQueryInterface $filter);

    /**
     * Returns facet filter.
     *
     * @return SearchQueryInterface
     */
    public function getFilter();

    /**
     * Creates facet value instance by config.
     *
     * @param array $config
     * @return FacetValueInterface
     */
    public function getValueInstance(array $config);

    /**
     * Creates multiple facet value instances by configs.
     *
     * @param array $configs
     * @return FacetValueInterface[]
     */
    public function getValueInstances(array $configs);


    /**
     * Sets facet context.
     *
     * @param mixed $context
     */
    public function setContext($context);

    /**
     * Returns facet context.
     *
     * @return mixed
     */
    public function getContext();

    /**
     * Whether facet has results.
     *
     * @return bool
     */
    public function isHasResults();

    /**
     * Whether to display facet value if it has no results.
     *
     * @return bool
     */
    public function isDisplayValuesWithoutResults();
}