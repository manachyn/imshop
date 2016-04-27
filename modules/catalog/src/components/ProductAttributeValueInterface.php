<?php

namespace im\catalog\components;

use im\eav\components\AttributeValueInterface;

interface ProductAttributeValueInterface extends AttributeValueInterface
{
    /**
     * Get product.
     *
     * @return ProductInterface
     */
    public function getProduct();

    /**
     * Set product.
     *
     * @param ProductInterface|null $product
     */
    public function setProduct(ProductInterface $product = null);
}
