<?php

namespace Robert430404\GuzzleFace\Factories;

use Robert430404\GuzzleFace\Annotations\Contracts\ConfigurationAnnotationInterface;

/**
 * Class ConfigFactory
 *
 * @package Robert430404\GuzzleFace\Factories
 */
class ConfigFactory
{
    /**
     * @var array
     */
    private $classAnnotations;

    /**
     * ConfigFactory constructor.
     *
     * @param array $classAnnotations
     */
    public function __construct(array $classAnnotations)
    {
        $this->classAnnotations = $classAnnotations;
    }

    /**
     * Makes the configuration from the class annotations.
     *
     * @return array
     */
    public function makeConfig(): array
    {
        $config = [];

        /**
         * Load up the global configurations for the client.
         *
         * @var ConfigurationAnnotationInterface $annotation
         */
        foreach ($this->classAnnotations as $annotation) {
            $key = $annotation->getConfigKey();

            if (isset($config[$key])) {
                $config[$key] = array_merge($config[$key], $annotation->getValue());

                continue;
            }

            $config[$key] = $annotation->getValue();
        }

        return $config ?? [];
    }
}