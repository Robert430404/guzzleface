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
     * @return string
     */
    public function getValue(): string;

    /**
     * Returns the corresponding guzzle configuration key.
     *
     * @return string
     */
    public function getConfigKey(): string;
}