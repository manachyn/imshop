<?php

namespace im\cms\components;

interface PageInterface
{
    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set title.
     *
     * @param string $title
     */
    public function setTitle($title);

    /**
     * Get URL.
     *
     * @return string
     */
    public function getUrl();

    /**
     * Set URL.
     *
     * @param string $url
     */
    public function setUrl($url);

    /**
     * Get metadata.
     *
     * @return PageMetaInterface
     */
    public function getPageMeta();

    /**
     * Set metadata.
     *
     * @param PageMetaInterface $pageMeta
     */
    public function setPageMeta($pageMeta);
} 