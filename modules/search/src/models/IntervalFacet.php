<?php

namespace im\search\models;

use im\search\components\query\facet\IntervalFacetInterface;

/**
 * Interval facet model class.
 *
 * @property string $from
 * @property string $to
 * @property int|float $interval
 */
class IntervalFacet extends Facet implements IntervalFacetInterface
{
    const TYPE = self::TYPE_INTERVAL;

    /**
     * @var FacetInterval[]
     */
    private $_intervals;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->type = self::TYPE;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['from', 'to', 'interval'], 'string', 'max' => 100]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @inheritdoc
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @inheritdoc
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @inheritdoc
     */
    public function getValues()
    {
        return $this->_intervals;
    }

    /**
     * @inheritdoc
     */
    public function setValues($values)
    {
        $this->_intervals = $values;
    }

    /**
     * @inheritdoc
     */
    public function getValueInstance(array $config)
    {
        return new FacetInterval($config);
    }
}
