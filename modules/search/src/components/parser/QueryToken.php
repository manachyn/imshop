<?php

namespace im\search\components\parser;

use yii\base\InvalidParamException;

class QueryToken
{
    const TYPE_WORD = 'word';
    const TYPE_PHRASE = 'phrase';
    const TYPE_NUMBER = 'number';
    const TYPE_OPERATOR = 'operator';
    const TYPE_SYNTAX = 'syntax';
    const TYPE_FIELD                = 'field';
    const TYPE_FIELD_INDICATOR      = 'field_indicator';
    const TYPE_LEFT_SQUARE_BRACKET     = 'left_square_bracket';
    const TYPE_RIGHT_SQUARE_BRACKET     = 'right_square_bracket';
    const TYPE_LEFT_PARENTHESIS     = 'left_parenthesis';
    const TYPE_RIGHT_PARENTHESIS     = 'right_parenthesis';

    const TYPE_AND_LEXEME           = 'and';
    const TYPE_OR_LEXEME            = 'or';
    const TYPE_NOT_LEXEME           = 'not';
    const TYPE_TO_LEXEME            = 'to';

    /**
     * Token type.
     *
     * @var string
     */
    public $type;

    /**
     * Token text.
     *
     * @var integer
     */
    public $text;

    /**
     * Token position within query.
     *
     * @var integer
     */
    public $position;

    function __construct($type, $text, $position)
    {
        $this->text = $text;
        $this->position = $position + 1;

        switch ($type) {
            case self::TYPE_WORD:
                switch (strtolower($text)) {
                    case 'and':
                        $this->type = self::TYPE_AND_LEXEME;
                        break;

                    case 'or':
                        $this->type = self::TYPE_OR_LEXEME;
                        break;

                    case 'not':
                        $this->type = self::TYPE_NOT_LEXEME;
                        break;

                    case 'to':
                        $this->type = self::TYPE_TO_LEXEME;
                        break;

                    default:
                        $this->type = self::TYPE_WORD;
                }
                break;

            case self::TYPE_OPERATOR:
                switch ($text) {
                    case '=':
                        $this->type = self::TYPE_FIELD_INDICATOR;
                        break;

                    case '!':
                        $this->type = self::TYPE_NOT_LEXEME;
                        break;

                    case '&&':
                        $this->type = self::TYPE_AND_LEXEME;
                        break;

                    case '-or-':
                    case '||':
                        $this->type = self::TYPE_OR_LEXEME;
                        break;

                    default:
                        throw new InvalidParamException("Unrecognized query operator: '{$text}'");
                }
                break;

            case self::TYPE_SYNTAX:
                switch ($text) {
                    case '[':
                        $this->type = self::TYPE_LEFT_SQUARE_BRACKET;
                        break;

                    case ']':
                        $this->type = self::TYPE_RIGHT_SQUARE_BRACKET;
                        break;

                    case '(':
                        $this->type = self::TYPE_LEFT_PARENTHESIS;
                        break;

                    case ')':
                        $this->type = self::TYPE_RIGHT_PARENTHESIS;
                        break;

                    default:
                        throw new InvalidParamException("Unrecognized query syntax lexeme: '{$text}'");
                }
                break;

            default:
                throw new InvalidParamException("Unrecognized lexeme type: '{$type}'");
        }
    }


}