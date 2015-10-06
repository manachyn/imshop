<?php

namespace im\search\components\index\provider;

/**
 * Describe interface of index data provider.
 *
 * @package im\search\components\index\provider
 */
interface IndexProviderInterface
{
    /**
     * Returns total count of objects for indexing.
     *
     * @param int $offset
     * @return int
     */
    public function countObjects($offset = 0);

    /**
     * Returns object for indexing.
     *
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function getObjects($limit = null, $offset = 0);

    /**
     * Returns object class.
     *
     * @return string
     */
    public function getObjectClass();

    /**
     * Sets object class.
     *
     * @param string $objectClass
     */
    public function setObjectClass($objectClass);
}