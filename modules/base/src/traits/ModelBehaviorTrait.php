<?php

namespace im\base\traits;

use im\base\interfaces\ModelBehaviorInterface;
use yii\base\Model;

/**
 * Class ModelBehaviorTrait.
 * @property Model $this
 * @package im\base\traits
 */
trait ModelBehaviorTrait
{
    public function load($data, $formName = null)
    {
        return parent::load($data, $formName) && $this->loadBehaviors($data);
    }

    /**
     * Populates the model behaviors with the data from end user.
     * @param array $data the data array.
     * @return boolean whether the model behaviors is successfully populated with some data.
     */
    public function loadBehaviors($data)
    {
        $loaded = true;
        foreach ($this->getBehaviors() as $behavior) {
            if ($behavior instanceof ModelBehaviorInterface) {
                if (!$behavior->load($data)) {
                    $loaded = false;
                }
            }
        }

        return $loaded;
    }
}