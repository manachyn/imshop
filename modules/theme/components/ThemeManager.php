<?php

namespace app\modules\theme\components;


use yii\base\Component;
use yii\base\Theme;

class ThemeManager extends Component {

    /** @var Theme */
    private $theme;

    /**
     * @param Theme $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * @return Theme
     */
    public function getTheme()
    {
        return $this->theme;
    }



} 