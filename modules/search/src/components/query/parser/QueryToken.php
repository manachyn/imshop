<?php

namespace im\search\components\query\parser;

use yii\base\InvalidParamException;

/**
 * Class QueryToken
 * @package im\search\components\query\parser
 */
class QueryToken
{
    /**
     * Token types constants
     */
    const TYPE_WHITE_SPACE = 'white_space';
    const TYPE_WORD = 'word';
    const TYPE_PHRASE = 'phrase';
    const TYPE_NUMBER = 'number';
    const TYPE_DATE = 'date';
    const TYPE_OPERATOR = 'operator';
    const TYPE_SYNTAX = 'syntax';
    const TYPE_LEFT_SQUARE_BRACKET = 'left_square_bracket';
    const TYPE_RIGHT_SQUARE_BRACKET = 'right_square_bracket';
    const TYPE_LEFT_PARENTHESIS = 'left_parenthesis';
    const TYPE_RIGHT_PARENTHESIS = 'right_parenthesis';
    const TYPE_AND_OPERATOR = 'and';
    const TYPE_OR_OPERATOR = 'or';
    const TYPE_NOT_OPERATOR = 'not';
    const TYPE_TO_LEXEME = 'to';

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

    /**
     * Creates query token.
     *
     * @param string $type
     * @param string $text
     * @param int $position
     */
    function __construct($type, $text, $position)
    {
        $this->text = $text;
        $this->position = $position + 1;

        switch ($type) {
            case self::TYPE_WORD:
                switch (strtolower($text)) {
                    case 'and':
                        $this->type = self::TYPE_AND_OPERATOR;
                        break;
                    case 'or':
                        $this->type = self::TYPE_OR_OPERATOR;
                        break;
                    case 'not':
                        $this->type = self::TYPE_NOT_OPERATOR;
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
                    case '>':
                    case '<':
                    case '>=':
                    case '<=':
                        $this->type = self::TYPE_OPERATOR;
                        break;
                    case 'not':
                    case '!':
                        $this->type = self::TYPE_NOT_OPERATOR;
                        break;
                    case 'and':
                    case '&':
                        $this->type = self::TYPE_AND_OPERATOR;
                        break;
                    case 'or':
                        $this->type = self::TYPE_OR_OPERATOR;
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
                    case '-':
                        $this->type = self::TYPE_WHITE_SPACE;
                        break;
                    default:
                        throw new InvalidParamException("Unrecognized query syntax lexeme: '{$text}'");
                }
                break;
            case self::TYPE_NUMBER:
                $this->type = self::TYPE_WORD;
                break;
            case self::TYPE_DATE:
                $this->type = self::TYPE_WORD;
                break;
            default:
                throw new InvalidParamException("Unrecognized lexeme type: '{$type}'");
        }
    }

    /**
     * Returns query token types.
     *
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_WHITE_SPACE,
            self::TYPE_WORD,
            self::TYPE_PHRASE,
            self::TYPE_NUMBER,
            self::TYPE_DATE,
            self::TYPE_OPERATOR,
            self::TYPE_SYNTAX,
            self::TYPE_LEFT_SQUARE_BRACKET,
            self::TYPE_RIGHT_SQUARE_BRACKET,
            self::TYPE_LEFT_PARENTHESIS,
            self::TYPE_RIGHT_PARENTHESIS,
            self::TYPE_AND_OPERATOR,
            self::TYPE_OR_OPERATOR,
            self::TYPE_NOT_OPERATOR,
            self::TYPE_TO_LEXEME
        ];
    }
}