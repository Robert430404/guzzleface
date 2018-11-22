<?php

namespace Robert430404\GuzzleFace\Annotations\Request;

use Doctrine\Common\Annotations\Annotation;
use Robert430404\GuzzleFace\Annotations\Contracts\ConfigurationAnnotationInterface;

/**
 * Class Header
 *
 * @Annotation
 * @Annotation\Target({"METHOD"})
 *
 * @package Robert430404\GuzzleFace\Annotations\Request
 */
class Headers extends Annotation
{
    /**
     * @var ConfigurationAnnotationInterface[]
     */
    public $headers;

    /**
     * @return ConfigurationAnnotationInterface[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}