<?php

namespace im\search\models;

use im\base\behaviors\RelationsBehavior;
use im\search\backend\Module;

/**
 * Terms facet model class.
 *
 * @property FacetTerm[] $terms
 */
class TermsFacet extends Facet
{
    const TYPE = self::TYPE_TERMS;

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
                    'terms' => ['deleteOnUnlink' => true]
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
            'terms' => Module::t('facet', 'Terms')
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTermsRelation()
    {
        return $this->hasMany(FacetTerm::className(), ['facet_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        return parent::load($data, $formName) && $this->loadTerms($data);
    }

    /**
     * Populates the model terms with the data array.
     *
     * @param array $data
     * @param string $formName
     * @return boolean
     */
    public function loadTerms($data, $formName = null)
    {
        if ($formName === null) {
            $formName = 'FacetTerm';
        }
        $terms = $this->getTermsFromData($data, $formName);
        $loaded = [];
        $isLoaded = true;
        if (!empty($data[$formName])) {
            foreach ($data[$formName] as $key => $termData) {
                $term = null;
                if (isset($terms[$key])) {
                    $term = $terms[$key];
                    if ($term->load($termData, '')) {
                        $loaded[] = $term;
                    } else {
                        $isLoaded = false;
                    }
                }
            }
        }

        if (isset($data[$formName])) {
            $this->terms = $loaded ?: null;
        }

        return $isLoaded;
    }

    /**
     * Gets terms from data array.
     *
     * @param array $data
     * @param string $formName
     * @return FacetTerm[]
     */
    protected function getTermsFromData($data, $formName = null)
    {
        if ($formName === null) {
            $formName = 'FacetTerm';
        }
        $terms = [];
        if (!empty($data[$formName])) {
            $pks = [];
            $existingTerms = [];
            foreach ($data[$formName] as $termData) {
                if (!empty($termData['id'])) {
                    $pks[] = $termData['id'];
                }
            }
            if ($pks) {
                $existingTerms = FacetTerm::find(['id' => $pks])->indexBy('id')->all();
            }
            foreach ($data[$formName] as $key => $termData) {
                if (!empty($termData['id']) && isset($existingTerms[$termData['id']])) {
                    $terms[$key] = $existingTerms[$termData['id']];
                } else {
                    $terms[$key] = new FacetTerm();
                }
            }
        }

        return $terms;
    }
}
