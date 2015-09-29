<?php

namespace im\fsm;

use yii\base\InvalidParamException;

/**
 * Class FSM - Finite State Machine.
 * 
 * @package im\fsm
 */
abstract class FSM
{
    /**
     * Machine States alphabet
     *
     * @var array
     */
    private $_states = array();

    /**
     * Current state
     *
     * @var integer|string
     */
    private $_currentState = null;

    /**
     * Input alphabet
     *
     * @var array
     */
    private $_inputAlphabet = [];

    /**
     * State transition table
     *
     * [sourceState][input] => targetState
     *
     * @var array
     */
    private $_transitions = [];

    /**
     * List of entry actions
     * Each action executes when entering the state
     *
     * [state] => action
     *
     * @var array
     */
    private $_entryActions = [];

    /**
     * List of exit actions
     * Each action executes when exiting the state
     *
     * [state] => action
     *
     * @var array
     */
    private $_exitActions = [];

    /**
     * List of input actions
     * Each action executes when entering the state
     *
     * [state][input] => action
     *
     * @var array
     */
    private $_inputActions = [];

    /**
     * List of input actions
     * Each action executes when entering the state
     *
     * [state1][state2] => action
     *
     * @var array
     */
    private $_transitionActions = [];

    /**
     * Finite State machine constructor
     *
     * $states is an array of integers or strings with a list of possible machine states
     * constructor treats fist list element as a sturt state (assignes it to $_current state).
     * It may be reassigned by setState() call.
     * States list may be empty and can be extended later by addState() or addStates() calls.
     *
     * $inputAphabet is the same as $states, but represents input alphabet
     * it also may be extended later by addInputSymbols() or addInputSymbol() calls.
     *
     * $rules parameter describes FSM transitions and has a structure:
     * array( array(sourseState, input, targetState[, inputAction]),
     *        array(sourseState, input, targetState[, inputAction]),
     *        array(sourseState, input, targetState[, inputAction]),
     *        ...
     *      )
     * Rules also can be added later by addRules() and addRule() calls.
     *
     * FSM actions are very flexible and may be defined by addEntryAction(), addExitAction(),
     * addInputAction() and addTransitionAction() calls.
     *
     * @param array $states
     * @param array $inputAlphabet
     * @param array $rules
     */
    public function __construct($states = array(), $inputAlphabet = array(), $rules = array())
    {
        $this->addStates($states);
        $this->addInputSymbols($inputAlphabet);
        $this->addRules($rules);
    }

    /**
     * Add states to the state machine
     *
     * @param array $states
     */
    public function addStates($states)
    {
        foreach ($states as $state) {
            $this->addState($state);
        }
    }

    /**
     * Add state to the state machine
     *
     * @param integer|string $state
     */
    public function addState($state)
    {
        $this->_states[$state] = $state;

        if ($this->_currentState === null) {
            $this->_currentState = $state;
        }
    }

    /**
     * Set FSM state.
     * No any action is invoked
     *
     * @param integer|string $state
     * @throws \yii\base\InvalidParamException
     */
    public function setState($state)
    {
        if (!isset($this->_states[$state])) {
            throw new InvalidParamException('State \'' . $state . '\' is not on of the possible FSM states.');
        }

        $this->_currentState = $state;
    }

    /**
     * Get FSM state.
     *
     * @return integer|string $state|null
     */
    public function getState()
    {
        return $this->_currentState;
    }

    /**
     * Add symbols to the input alphabet
     *
     * @param array $inputAphabet
     */
    public function addInputSymbols($inputAphabet)
    {
        foreach ($inputAphabet as $inputSymbol) {
            $this->addInputSymbol($inputSymbol);
        }
    }

    /**
     * Add symbol to the input alphabet
     *
     * @param integer|string $inputSymbol
     */
    public function addInputSymbol($inputSymbol)
    {
        $this->_inputAlphabet[$inputSymbol] = $inputSymbol;
    }


    /**
     * Add transition rules
     *
     * array structure:
     * array( array(sourseState, input, targetState[, inputAction]),
     *        array(sourseState, input, targetState[, inputAction]),
     *        array(sourseState, input, targetState[, inputAction]),
     *        ...
     *      )
     *
     * @param array $rules
     */
    public function addRules($rules)
    {
        foreach ($rules as $rule) {
            $this->addrule($rule[0], $rule[1], $rule[2], isset($rule[3])?$rule[3]:null);
        }
    }

    /**
     * Add symbol to the input alphabet
     *
     * @param integer|string $sourceState
     * @param integer|string $input
     * @param integer|string $targetState
     * @param FSMAction|null $inputAction
     * @throws InvalidParamException
     * @throws \RuntimeException
     */
    public function addRule($sourceState, $input, $targetState, $inputAction = null)
    {
        if (!isset($this->_states[$sourceState])) {
            throw new InvalidParamException('Undefined source state (' . $sourceState . ').');
        }
        if (!isset($this->_states[$targetState])) {
            throw new InvalidParamException('Undefined target state (' . $targetState . ').');
        }
        if (!isset($this->_inputAlphabet[$input])) {
            throw new InvalidParamException('Undefined input symbol (' . $input . ').');
        }

        if (!isset($this->_transitions[$sourceState])) {
            $this->_transitions[$sourceState] = array();
        }
        if (isset($this->_transitions[$sourceState][$input])) {
            throw new \RuntimeException('Rule for {state,input} pair (' . $sourceState . ', '. $input . ') is already defined.');
        }

        $this->_transitions[$sourceState][$input] = $targetState;


        if ($inputAction !== null) {
            $this->addInputAction($sourceState, $input, $inputAction);
        }
    }


    /**
     * Add state entry action.
     * Several entry actions are allowed.
     * Action execution order is defined by addEntryAction() calls
     *
     * @param integer|string $state
     * @param FSMAction $action
     * @throws InvalidParamException
     */
    public function addEntryAction($state, FSMAction $action)
    {
        if (!isset($this->_states[$state])) {
            throw new InvalidParamException('Undefined state (' . $state. ').');
        }

        if (!isset($this->_entryActions[$state])) {
            $this->_entryActions[$state] = array();
        }

        $this->_entryActions[$state][] = $action;
    }

    /**
     * Add state exit action.
     * Several exit actions are allowed.
     * Action execution order is defined by addEntryAction() calls
     *
     * @param integer|string $state
     * @param FSMAction $action
     * @throws InvalidParamException
     */
    public function addExitAction($state, FSMAction $action)
    {
        if (!isset($this->_states[$state])) {
            throw new InvalidParamException('Undefined state (' . $state. ').');
        }

        if (!isset($this->_exitActions[$state])) {
            $this->_exitActions[$state] = array();
        }

        $this->_exitActions[$state][] = $action;
    }

    /**
     * Add input action (defined by {state, input} pair).
     * Several input actions are allowed.
     * Action execution order is defined by addInputAction() calls
     *
     * @param integer|string $state
     * @param integer|string $inputSymbol
     * @param FSMAction $action
     * @throws InvalidParamException
     */
    public function addInputAction($state, $inputSymbol, FSMAction $action)
    {
        if (!isset($this->_states[$state])) {
            throw new InvalidParamException('Undefined state (' . $state. ').');
        }
        if (!isset($this->_inputAlphabet[$inputSymbol])) {
            throw new InvalidParamException('Undefined input symbol (' . $inputSymbol. ').');
        }

        if (!isset($this->_inputActions[$state])) {
            $this->_inputActions[$state] = array();
        }
        if (!isset($this->_inputActions[$state][$inputSymbol])) {
            $this->_inputActions[$state][$inputSymbol] = array();
        }

        $this->_inputActions[$state][$inputSymbol][] = $action;
    }

    /**
     * Add transition action (defined by {state, input} pair).
     * Several transition actions are allowed.
     * Action execution order is defined by addTransitionAction() calls
     *
     * @param integer|string $sourceState
     * @param integer|string $targetState
     * @param FSMAction $action
     * @throws InvalidParamException
     */
    public function addTransitionAction($sourceState, $targetState, FSMAction $action)
    {
        if (!isset($this->_states[$sourceState])) {
            throw new InvalidParamException('Undefined source state (' . $sourceState. ').');
        }
        if (!isset($this->_states[$targetState])) {
            throw new InvalidParamException('Undefined source state (' . $targetState. ').');
        }

        if (!isset($this->_transitionActions[$sourceState])) {
            $this->_transitionActions[$sourceState] = array();
        }
        if (!isset($this->_transitionActions[$sourceState][$targetState])) {
            $this->_transitionActions[$sourceState][$targetState] = array();
        }

        $this->_transitionActions[$sourceState][$targetState][] = $action;
    }


    /**
     * Process an input
     *
     * @param mixed $input
     * @throws \RuntimeException
     * @throws InvalidParamException
     */
    public function process($input)
    {
        if (!isset($this->_transitions[$this->_currentState])) {
            throw new \RuntimeException('There is no any rule for current state (' . $this->_currentState . ').');
        }
        if (!isset($this->_transitions[$this->_currentState][$input])) {
            throw new InvalidParamException('There is no any rule for {current state, input} pair (' . $this->_currentState . ', ' . $input . ').');
        }

        $sourceState = $this->_currentState;
        $targetState = $this->_transitions[$this->_currentState][$input];

        /** @var FSMAction $action */
        if ($sourceState != $targetState  &&  isset($this->_exitActions[$sourceState])) {
            foreach ($this->_exitActions[$sourceState] as $action) {
                $action->doAction();
            }
        }
        if (isset($this->_inputActions[$sourceState]) &&
            isset($this->_inputActions[$sourceState][$input])) {
            foreach ($this->_inputActions[$sourceState][$input] as $action) {
                $action->doAction();
            }
        }

        $this->_currentState = $targetState;

        if (isset($this->_transitionActions[$sourceState]) &&
            isset($this->_transitionActions[$sourceState][$targetState])) {
            foreach ($this->_transitionActions[$sourceState][$targetState] as $action) {
                $action->doAction();
            }
        }
        if ($sourceState != $targetState  &&  isset($this->_entryActions[$targetState])) {
            foreach ($this->_entryActions[$targetState] as $action) {
                $action->doAction();
            }
        }
    }

    /**
     * @throws \ZendSearch\Lucene\Exception\RuntimeException
     */
    public function reset()
    {
        if (count($this->_states) == 0) {
            throw new \RuntimeException('There is no any state defined for FSM.');
        }

        $this->_currentState = $this->_states[0];
    }
}
