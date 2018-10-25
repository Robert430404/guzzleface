<?php

namespace Robert430404\GuzzleFace\Annotations;

use Doctrine\Common\Annotations\Annotation;
use Robert430404\GuzzleFace\Annotations\Contracts\ConfigurationAnnotationInterface;

/**
 * Class BaseUrl
 *
 * @Annotation
 * @Annotation\Target({"CLASS"})
 *
 * @package Robert430404\GuzzleFace\Annotations
 */
class BaseUrl extends Annotation implements ConfigurationAnnotationInterface
{
    /**
     * @Annotation\Required
     *
     * @var string
     */
    public $host;

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->host;
    }

    /**
     * Returns the corresponding guzzle configuration key.
     *
     * @return string
     */
    public function getConfigKey(): string
    {
        return 'base_uri';
    }
}