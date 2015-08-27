<?php

namespace im\search\components\index;

use im\search\models\Index;
use yii\base\Component;
use yii\base\InvalidParamException;

class IndexManager extends Component
{
    /**
     * @var array
     */
    public $indexes = [];

    /**
     * Gets an index by its name.
     *
     * @param string $name
     * @return IndexInterface
     * @throws \InvalidArgumentException
     */
    public function getIndex($name)
    {
        if (!isset($this->indexes[$name])) {
            $index = Index::findOne(['name' => $name]);
            if ($index) {
                $this->indexes[$name] = $index;
            } else {
                throw new InvalidParamException("The index '$name' does not exist");
            }
        }

        return $this->indexes[$name];
    }
}