<?php

namespace im\base\interfaces;

interface TimestampableInterface
{
    /**
     * Get creation time.
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Get the time of last update.
     *
     * @return \DateTime
     */
    public function getUpdatedAt();

    /**
     * Set creation time.
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt);

    /**
     * Set the time of last update.
     *
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt);
}
