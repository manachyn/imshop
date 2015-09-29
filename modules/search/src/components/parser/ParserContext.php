<?php

namespace im\search\components\parser;


class ParserContext {
    protected $_builder = null;
    protected $_children_contexts = array();
    protected $_raw_content = array();
    protected $_operations = array();

    const T_NUMBER = 1;
    const T_OPERATOR = 2;
    const T_SCOPE_OPEN = 3;
    const T_SCOPE_CLOSE = 4;
    const T_SIN_SCOPE_OPEN = 5;
    const T_COS_SCOPE_OPEN = 6;
    const T_TAN_SCOPE_OPEN = 7;
    const T_SQRT_SCOPE_OPEN = 8;

    public function set_builder(Parser $builder) {
        $this->_builder = $builder;
    }

    public function __toString() {
        return implode('', $this->_raw_content);
    }

    public function add_operation( $operation ) {
        $this->_operations[] = $operation;
    }

    /**
     * handle the next token from the tokenized list. example actions
     * on a token would be to add it to the current context expression list,
     * to push a new context on the the context stack, or pop a context off the
     * stack.
     */
    public function handle_token( $token ) {
        $type = null;

        if ( in_array( $token, array('*','/','+','-','^') ) ) $type = self::T_OPERATOR;
        if ( $token === ')' ) $type = self::T_SCOPE_CLOSE;
        if ( $token === '(' ) $type = self::T_SCOPE_OPEN;
        if ( $token === 'sin(' ) $type = self::T_SIN_SCOPE_OPEN;
        if ( $token === 'cos(' ) $type = self::T_COS_SCOPE_OPEN;
        if ( $token === 'tan(' ) $type = self::T_TAN_SCOPE_OPEN;
        if ( $token === 'sqrt(' ) $type = self::T_SQRT_SCOPE_OPEN;

        if ( is_null( $type ) ) {
            if ( is_numeric( $token ) ) {
                $type = self::T_NUMBER;
                $token = (float)$token;
            }
        }

        switch ( $type ) {
            case self::T_NUMBER:
            case self::T_OPERATOR:
                $this->_operations[] = $token;
                break;
            case self::T_SCOPE_OPEN:
                $this->_builder->push_context( new namespace\Scope() );
                break;
            case self::T_SIN_SCOPE_OPEN:
                $this->_builder->push_context( new namespace\SineScope() );
                break;
            case self::T_COS_SCOPE_OPEN:
                $this->_builder->push_context( new namespace\CosineScope() );
                break;
            case self::T_TAN_SCOPE_OPEN:
                $this->_builder->push_context( new namespace\TangentScope() );
                break;
            case self::T_SQRT_SCOPE_OPEN:
                $this->_builder->push_context( new namespace\SqrtScope() );
                break;
            case self::T_SCOPE_CLOSE:
                $scope_operation = $this->_builder->pop_context();
                $new_context = $this->_builder->get_context();
                if ( is_null( $scope_operation ) || ( ! $new_context ) ) {
                    # this means there are more closing parentheses than openning
                    throw new \exprlib\exceptions\OutOfScopeException();
                }
                $new_context->add_operation( $scope_operation );
                break;
            default:
                throw new \exprlib\exceptions\UnknownTokenException($token);
                break;
        }
    }

    /**
     * order of operations:
     * - parentheses, these should all ready be executed before this method is called
     * - exponents, first order
     * - mult/divi, second order
     * - addi/subt, third order
     */
    protected function _expression_loop( & $operation_list ) {
        while ( list( $i, $operation ) = each ( $operation_list ) ) {
            if ( ! in_array( $operation, array('^','*','/','+','-') ) ) continue;

            $left =  isset( $operation_list[ $i - 1 ] ) ? (float)$operation_list[ $i - 1 ] : null;
            $right = isset( $operation_list[ $i + 1 ] ) ? (float)$operation_list[ $i + 1 ] : null;

            # if ( is_null( $left ) || is_null( $right ) ) throw new \Exception('syntax error');

            $first_order = ( in_array('^', $operation_list) );
            $second_order = ( in_array('*', $operation_list ) || in_array('/', $operation_list ) );
            $third_order = ( in_array('-', $operation_list ) || in_array('+', $operation_list ) );

            $remove_sides = true;
            if ( $first_order ) {
                switch( $operation ) {
                    case '^': $operation_list[ $i ] = pow( (float)$left, (float)$right ); break;
                    default: $remove_sides = false; break;
                }
            } elseif ( $second_order ) {
                switch ( $operation ) {
                    case '*': $operation_list[ $i ] = (float)($left * $right); break;
                    case '/': $operation_list[ $i ] = (float)($left / $right); break;
                    default: $remove_sides = false; break;
                }
            } elseif ( $third_order ) {
                switch ( $operation ) {
                    case '+': $operation_list[ $i ] = (float)($left + $right); break;
                    case '-': $operation_list[ $i ] = (float)($left - $right); break;
                    default: $remove_sides = false; break;
                }
            }

            if ( $remove_sides ) {
                unset( $operation_list[ $i + 1 ], $operation_list[ $i - 1 ] );
                reset( $operation_list = array_values( $operation_list ) );
            }
        }
        if ( count( $operation_list ) === 1 ) return end( $operation_list );
        return false;
    }

    # order of operations:
    # - sub scopes first
    # - multiplication, division
    # - addition, subtraction
    # evaluating all the sub scopes (recursivly):
    public function evaluate() {
        foreach ( $this->_operations as $i => $operation ) {
            if ( is_object( $operation ) ) {
                $this->_operations[ $i ] = $operation->evaluate();
            }
        }

        $operation_list = $this->_operations;

        while ( true ) {
            $operation_check = $operation_list;
            $result = $this->_expression_loop( $operation_list );
            if ( $result !== false ) return $result;
            if ( $operation_check === $operation_list ) {
                break;
            } else {
                reset( $operation_list = array_values( $operation_list ) );
            }
        }
        throw new \Exception('failed... here');
    }
}