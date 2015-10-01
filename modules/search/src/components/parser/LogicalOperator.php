<?php

namespace im\search\components\parser;

class LogicalOperator extends Operator
{
    const TYPE_AND = 'and';
    const TYPE_OR = 'or';
    const TYPE_NOT = 'not';
}