<?php

namespace im\forms\components;

use yii\bootstrap\Tabs;
use yii\helpers\Html;

/**
 * Class TabSet represents tabbed set of fields.
 *
 * @package im\forms\components
 */
class TabSet extends FieldSet
{
    /**
     * @inheritdoc
     */
    public function render($params = [])
    {
        if (!empty($params['tabbed'])) {
            $tabs = [];
            foreach ($this->items as $item) {
                if ($item instanceof Tab) {
                    $tabs[] = [
                        'label' => $item->getLabel(),
                        'content' => $item->render($params)
                    ];
                }
            }
            $output = Html::beginTag('div', ['class' => 'nav-tabs-custom']);
            $output .= Tabs::widget(['items' => $tabs]);
            $output .= Html::endTag('div');
            return $output;
        } else {
            return parent::render($params);
        }
    }

} 