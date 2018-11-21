<?php

namespace Robert430404\GuzzleFace\Annotations\Request\Headers;

use Doctrine\Common\Annotations\Annotation;
use Robert430404\GuzzleFace\Annotations\Contracts\ConfigurationAnnotationInterface;
use Robert430404\GuzzleFace\Enumerations\ConfigurationEnumerations;

/**
 * Class CustomHeader
 *
 * @Annotation
 * @Annotation\Target({"METHOD", "CLASS"})
 *
 * @package Robert430404\GuzzleFace\Annotations\Request\Headers
 */
class CustomHeader extends Annotation implements ConfigurationAnnotationInterface
{
    /**
     * @Annotation\Required
     *
     * @var string
     */
    public $name;

    /**
     * @Annotation\Required
     *
     * @var string
     */
    public $body;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Returns the value that the configuration holds.
     *
     * @return mixed
     */
    public function getValue()
    {
        return [
            $this->getName() => $this->getBody(),
        ];
    }

    /**
     * Returns the corresponding guzzle configuration key.
     *
     * @return string
     */
    public function getConfigKey(): string
    {
        return ConfigurationEnumerations::HEADER_CONFIG_KEY;
    }
}