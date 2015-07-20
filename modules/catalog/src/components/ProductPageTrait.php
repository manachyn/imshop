<?php

namespace im\catalog\components;

use im\base\interfaces\TypeableEntityInterface;
use im\catalog\models\ProductMeta;
use Yii;

trait ProductPageTrait
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageMetaRelation()
    {
        return $this->hasOne(ProductMeta::className(), ['entity_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return $this->slug;
    }

    /**
     * {@inheritdoc}
     */
    public function setUrl($url)
    {
        $this->slug = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getPageMeta()
    {
        $meta = $this->relatedPageMeta;
        if ($meta === null) {
            $meta = Yii::createObject(ProductMeta::className());
        }
        return $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function setPageMeta($pageMeta)
    {
        $this->relatedPageMeta = $pageMeta;
    }

    /**
     * @return string
     */
    private function getEntityType() {
        return $this instanceof TypeableEntityInterface ? $this->getEntityType() : get_class($this);
    }
} 