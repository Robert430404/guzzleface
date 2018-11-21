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
class UserAgent extends Annotation implements ConfigurationAnnotationInterface
{
    /**
     * @Annotation\Required
     *
     * @var string
     */
    public $agent;

    /**
     * @return string
     */
    public function getAgent(): string
    {
        return $this->agent;
    }

    /**
     * Returns the value that the configuration holds.
     *
     * @return mixed
     */
    public function getValue()
    {
        return [
            'User-Agent' => $this->getAgent(),
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