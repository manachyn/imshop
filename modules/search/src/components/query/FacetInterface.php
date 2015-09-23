<?php

namespace im\search\components\query;

interface FacetInterface
{
    /**
     * @return string
     */
    public function getName();

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
     * @param array $config
     * @return FacetValueInterface
     */
    public function getValueInstance(array $config);
}