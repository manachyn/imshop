<?php

namespace im\flow\scenario;

use im\flow\builder\FlowBuilderInterface;

/**
 * Interface FlowScenarioInterface
 * @package im\flow\scenario
 */
interface FlowScenarioInterface
{
    /**
     * Builds the whole flow.
     *
     * @param FlowBuilderInterface $builder
     */
    public function build(FlowBuilderInterface $builder);
}
