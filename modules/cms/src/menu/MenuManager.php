<?php

namespace im\cms\menu;

use yii\base\Component;
use yii\web\NotFoundHttpException;
use Yii;

class MenuManager extends Component {

    /** @var array|MenuDescriptorInterface[] */
    private $_menus;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        foreach ($this->_menus as $key => $menu) {
            if (!$menu instanceof MenuDescriptorInterface) {
                $this->_menus[$key] = Yii::createObject($menu);
            }
        }
    }

    /**
     * @param MenuDescriptorInterface[] $menus
     */
    public function setMenus($menus)
    {
        $this->_menus = $menus;
    }

    /**
     * @return MenuDescriptorInterface[]
     */
    public function getMenus()
    {
        return $this->_menus;
    }

    /**
     * @param string $id
     * @return MenuDescriptorInterface
     * @throws \yii\web\NotFoundHttpException
     */
    public function getMenu($id)
    {
        foreach ($this->_menus as $menu) {
            if ($menu->id == $id)
                return $menu;
        }
        throw new NotFoundHttpException("Menu not found: $id");
    }
} 