<?php

namespace Robert430404\GuzzleFace\Annotations\Request;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class Endpoint
 *
 * @Annotation
 * @Annotation\Target({"METHOD"})
 *
 * @package Robert430404\GuzzleFace\Annotations\Request
 */
class Endpoint extends Annotation
{
    /**
     * @Annotation\Required
     *
     * @var string
     */
    public $uri;

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}