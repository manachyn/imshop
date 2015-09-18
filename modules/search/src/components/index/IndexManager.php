<?php

namespace im\search\components\index;

use im\search\models\Index;
use yii\base\Component;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;

class IndexManager extends Component
{
    /**
     * @var Index[]
     */
    public $indexes = [];

    /**
     * Gets an index by its name.
     *
     * @param string $name
     * @return IndexInterface|null
     */
    public function getIndex($name)
    {
        if (!isset($this->indexes[$name])) {
            $this->indexes[$name] = Index::findOne(['name' => $name]);
        }

        return $this->indexes[$name];
    }

    /**
     * Gets an index by its type.
     *
     * @param string $type
     * @return IndexInterface|null
     */
    public function getIndexByType($type)
    {
        foreach ($this->indexes as $index) {
            if ($index->type === $type) {
                return $index;
            }
        }
        /** @var Index $index */
        $index = Index::findOne(['type' => $type]);
        if ($index) {
            $this->indexes[$index->name] = $index;
        }

        return $index;
    }
}