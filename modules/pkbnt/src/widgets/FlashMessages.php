<?php

namespace im\pkbnt\widgets;

use yii\bootstrap\Alert;
use yii\bootstrap\Widget;
use Yii;

class FlashMessages extends Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - $key is the name of the session flash variable
     * - $value is the bootstrap alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        'error' => 'danger',
        'danger' => 'danger',
        'success' => 'success',
        'info' => 'info',
        'warning' => 'warning'
    ];

    /**
     * @var array the options for rendering the close button tag.
     */
    public $closeButton = [];

    public function init()
    {
        parent::init();

        $session = Yii::$app->getSession();
        $flashes = $session->getAllFlashes();
        $appendCss = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        foreach ($flashes as $type => $message) {
            /* initialize css class for each alert box */
            $this->options['class'] = 'alert-' . $this->alertTypes[$type] . $appendCss;

            /* assign unique id to each alert box */
            $this->options['id'] = $this->getId() . '-' . $type;

            echo Alert::widget(
                [
                    'type' => $type,
                    'body' => $message,
                    'closeButton' => $this->closeButton,
                    'options' => $this->options
                ]
            );

            $session->removeFlash($type);
        }
    }
}