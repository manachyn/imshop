<?php

namespace im\search\models;

use im\base\behaviors\RelationsBehavior;
use im\search\components\query\facet\RangeFacetInterface;
use im\search\Module;

/**
 * Range facet model class.
 *
 * @property FacetRange[] $ranges
 */
class RangeFacet extends Facet implements RangeFacetInterface
{
    const TYPE = self::TYPE_RANGE;

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
    public function behaviors()
    {
        return [
            'relations' => [
                'class' => RelationsBehavior::className(),
                'settings' => [
                    'ranges' => ['deleteOnUnlink' => true]
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'ranges' => Module::t('facet', 'Ranges')
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRangesRelation()
    {
        return $this->hasMany(FacetRange::className(), ['facet_id' => 'id'])->inverseOf('facetRelation');
    }

    /**
     * @inheritdoc
     */
    public function getRanges()
    {
        if (!$this->isRelationPopulated('ranges')) {
            $this->populateRelation('ranges', $this->getRangesRelation()->orderBy('sort')->findFor('ranges', $this));
            return $this->getRelatedRecords()['ranges'];
        }

        return $this->getRelatedRecords()['ranges'];
    }

    /**
     * @inheritdoc
     */
    public function getValues()
    {
        return $this->getRanges();
    }

    /**
     * @inheritdoc
     */
    public function setValues($values)
    {
        $this->populateRelation('ranges', $values);
    }

    /**
     * @inheritdoc
     */
    public function getValueInstance(array $config)
    {
        return new FacetRange($config);
    }

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        return parent::load($data, $formName) && $this->loadRanges($data);
    }

    /**
     * Populates the model ranges with the data array.
     *
     * @param array $data
     * @param string $formName
     * @return boolean
     */
    public function loadRanges($data, $formName = null)
    {
        if ($formName === null) {
            $formName = 'FacetRange';
        }
        $ranges = $this->getRangesFromData($data, $formName);
        $loaded = [];
        $isLoaded = true;
        if (!empty($data[$formName])) {
            foreach ($data[$formName] as $key => $rangeData) {
                $range = null;
                if (isset($ranges[$key])) {
                    $range = $ranges[$key];
                    if ($range->load($rangeData, '')) {
                        $loaded[] = $range;
                    } else {
                        $isLoaded = false;
                    }
                }
            }
        }

        if (isset($data[$formName])) {
            $this->ranges = $loaded ?: null;
        }

        return $isLoaded;
    }

    /**
     * Gets ranges from data array.
     *
     * @param array $data
     * @param string $formName
     * @return FacetRange[]
     */
    protected function getRangesFromData($data, $formName = null)
    {
        if ($formName === null) {
            $formName = 'FacetRange';
        }
        $ranges = [];
        if (!empty($data[$formName])) {
            $pks = [];
            $existingRanges = [];
            foreach ($data[$formName] as $rangeData) {
                if (!empty($rangeData['id'])) {
                    $pks[] = $rangeData['id'];
                }
            }
            if ($pks) {
                $existingRanges = FacetRange::find(['id' => $pks])->indexBy('id')->all();
            }
            foreach ($data[$formName] as $key => $rangeData) {
                if (!empty($rangeData['id']) && isset($existingRanges[$rangeData['id']])) {
                    $ranges[$key] = $existingRanges[$rangeData['id']];
                } else {
                    $ranges[$key] = new FacetRange();
                }
            }
        }

        return $ranges;
    }
}
