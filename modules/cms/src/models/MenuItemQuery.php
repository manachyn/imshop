<?php

namespace im\cms\models;

use im\tree\models\TreeQuery;

/**
 * Class MenuItemQuery
 * @package im\catalog\models
 * @method MenuItemQuery roots
 * @method MenuItemQuery leaves
 */
class MenuItemQuery extends TreeQuery
{
    /**
     * @param $status
     * @return MenuItemQuery
     */
    public function status($status)
    {
        $this->andWhere(['status' => $status]);

        return $this;
    }

    /**
     * @return MenuItemQuery
     */
    public function active()
    {
        $this->andWhere(['status' => MenuItem::STATUS_ACTIVE]);

        return $this;
    }

    /**
     * @return MenuItemQuery
     */
    public function inactive()
    {
        $this->andWhere(['status' => MenuItem::STATUS_INACTIVE]);

        return $this;
    }
}