<?php
namespace app\modules\theme\components;

interface ThemeProviderInterface {

    /**
     * Returns the menus locations in the layout.
     * @return array
     */
    public function getMenusLocations();

    /**
     * Returns the widget areas locations in the layout.
     * @return array
     */
    public function getWidgetAreasLocations();

    /**
     * Returns the banner areas locations in the layout.
     * @return array
     */
    public function getBannersAreasLocations();
} 