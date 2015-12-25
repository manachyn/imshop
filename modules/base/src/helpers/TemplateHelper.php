<?php

namespace im\base\helpers;

class TemplateHelper
{
    /**
     * Evaluates template.
     *
     * @param string $template
     * @param array $params
     * @return string
     */
    public static function evaluateTemplate($template, array $params)
    {
        return preg_replace_callback('|{(.*?)}|', function ($matches) use ($params) {
            $value = self::evaluateExpression($matches[1], $params);
            return $value !== null ? $value : $matches[0];
        }, $template);
    }

    /**
     * Evaluates expression.
     *
     * @param string $expression
     * @param array $params
     * @return mixed
     */
    private static function evaluateExpression($expression, array $params)
    {
        $parts = explode('.', $expression);
        $value = !empty($params[$parts[0]]) ? $params[array_shift($parts)] : null;
        if ($parts) {
            if (is_object($value)) {
                $value = $value->{array_shift($parts)};
            } elseif (is_array($value)) {
                $value = $value[array_shift($parts)];
            }
            if ($parts) {
                array_unshift($parts, 'obj');
                $expression = implode('.', $parts);
                $value = self::evaluateExpression($expression, ['obj' => $value]);
            }
        }

        return $value;
    }
}