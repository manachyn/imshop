<?php

namespace im\cms;

use Yii;

class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\cms\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->modules = [
            'backend' => [
                'class' => 'im\cms\backend\Module'
            ],
        ];
    }

    /**
     * Translate a message to the specified language using module translations.
     *
     * @param string $category the message category.
     * @param string $message the message to be translated.
     * @param array $params the parameters that will be used to replace the corresponding placeholders in the message.
     * @param string $language the language code.
     * @return string the translated message.
     * @see \Yii::t()
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/cms/' . $category, $message, $params, $language);
    }
}
