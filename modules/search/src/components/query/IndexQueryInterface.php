<?php

namespace im\search\components\query;

use im\search\components\transformer\DocumentToObjectTransformerInterface;

interface IndexQueryInterface
{
    /**
     * @return DocumentToObjectTransformerInterface
     */
    public function getTransformer();

    /**
     * @param DocumentToObjectTransformerInterface $transformer
     */
    public function setTransformer(DocumentToObjectTransformerInterface $transformer);
}