<?php

namespace im\search\models;

use im\search\backend\Module;

/**
 * Interval facet model class.
 *
 * @property string $from
 * @property string $to
 * @property string $interval
 */
class IntervalFacet extends Facet
{
    const TYPE = self::TYPE_INTERVAL;

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
}
