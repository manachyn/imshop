<?php

namespace im\search\components\query\facet;

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
     * Creates facet value instance by config.
     *
     * @param array $config
     * @return FacetValueInterface
     */
    public function getValueInstance(array $config);
}