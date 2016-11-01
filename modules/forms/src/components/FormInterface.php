<?php

namespace im\forms\components;

/**
 * Interface FormInterface
 * @package im\forms\components
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
interface FormInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function render();
}