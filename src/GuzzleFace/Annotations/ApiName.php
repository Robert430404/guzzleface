<?php

namespace Robert430404\GuzzleFace\Annotations;

use Doctrine\Common\Annotations\Annotation;
use Robert430404\GuzzleFace\Annotations\Contracts\ConfigurationAnnotationInterface;
use Robert430404\GuzzleFace\Enumerations\ConfigurationEnumerations;

/**
 * Class ApiName
 *
 * @Annotation
 * @Annotation\Target({"CLASS"})
 *
 * @package Robert430404\GuzzleFace\Annotations
 */
class ApiName extends Annotation implements ConfigurationAnnotationInterface
{
    /**
     * @Annotation\Required
     *
     * @var string
     */
    public $name;

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->name;
    }

    /**
     * Returns the corresponding guzzle configuration key.
     *
     * @return string
     */
    public function getConfigKey(): string
    {
        return ConfigurationEnumerations::API_NAME_CONFIG_KEY;
    }
}