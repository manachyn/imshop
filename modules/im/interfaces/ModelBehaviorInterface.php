<?php

namespace im\base\interfaces;

interface ModelBehaviorInterface
{
    /**
     * Populates the model behavior with the data from end user.
     * @param array $data the data array.
     * @return boolean whether the model behavior is successfully populated with some data.
     */
    public function load($data);
} 