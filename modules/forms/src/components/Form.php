<?php

namespace im\forms\components;

/**
 * Class StaticForm
 * @package im\forms\components
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
abstract class StaticForm implements FormInterface
{
    /**
     * @return string
     */
    abstract public function getView();

    /**
     * @inheritdoc
     */
    public function render()
    {
        // TODO: Implement render() method.
    }
}