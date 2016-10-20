<?php

namespace im\cms\models\widgets;

/**
 * Class ModelWidget
 * @author Ivan Manachyn <manachyn@gmail.com>
 * @property int $model_id
 */
abstract class ModelWidget extends Widget
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['model_id'], 'safe']
        ]);
    }
}