<?php

namespace im\catalog\components;

use im\eav\components\AttributesHolderInterface;

interface ProductTypeSubjectInterface extends AttributesHolderInterface
{
    /**
     * Get product type.
     *
     * @return ProductTypeInterface|null
     */
    public function getProductType();

    /**
     * Set product type.
     *
     * @param ProductTypeInterface $type
     */
    public function setProductType(ProductTypeInterface $type = null);
} 