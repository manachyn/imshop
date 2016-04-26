<?php

namespace im\cms\menu;

interface MenuProviderInterface {

    /**
     * @param string $menuType
     * @return array
     */
    public function getMenuItems($menuType);

} 