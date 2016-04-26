<?php

namespace im\base\context;

use yii\base\Model;

/**
 * Interface ModelContextInterface
 * @package im\base\context
 */
interface ModelContextInterface
{
    /**
     * Returns context model.
     *
     * @return Model
     */
    public function getModel();

    /**
     * Sets context model.
     *
     * @param Model $model
     */
    public function setModel(Model $model);
}