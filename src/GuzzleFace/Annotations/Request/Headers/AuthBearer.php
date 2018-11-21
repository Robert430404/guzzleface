<?php

namespace Robert430404\GuzzleFace\Annotations\Request\Headers;

use Doctrine\Common\Annotations\Annotation;
use Robert430404\GuzzleFace\Annotations\Contracts\ConfigurationAnnotationInterface;
use Robert430404\GuzzleFace\Enumerations\ConfigurationEnumerations;

/**
 * Class AuthBearer
 *
 * @Annotation
 * @Annotation\Target({"METHOD", "CLASS"})
 *
 * @package Robert430404\GuzzleFace\Annotations\Request\Headers
 */
class AuthBearer extends Annotation implements ConfigurationAnnotationInterface
{
    /**
     * @Annotation\Required
     *
     * @var string
     */
    public $token;

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Returns the value that the configuration holds.
     *
     * @return mixed
     */
    public function getValue()
    {
        return [
            'Authorization' => "Bearer {$this->getToken()}",
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