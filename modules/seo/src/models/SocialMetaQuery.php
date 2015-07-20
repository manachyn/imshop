<?php

namespace im\seo\models;

use yii\db\ActiveQuery;

class SocialMetaQuery extends ActiveQuery
{
    public $socialType;

    public function prepare($builder)
    {
        if ($this->socialType !== null) {
            $this->andWhere(['social_type' => $this->socialType]);
        }
        return parent::prepare($builder);
    }
}