<?php

namespace Robert430404\GuzzleFace\Annotations\Contracts;

/**
 * Interface ConfigurationAnnotationInterface
 *
 * This is the contract that all configuration based annotations implement.
 *
 * @package Robert430404\GuzzleFace\Annotations\Contracts
 */
interface ConfigurationAnnotationInterface
{
    /**
     * Returns the value that the configuration holds.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Returns the corresponding guzzle configuration key.
     *
     * @return string
     */
    public function getConfigKey(): string;
}