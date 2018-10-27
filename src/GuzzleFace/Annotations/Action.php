<?php

namespace Robert430404\GuzzleFace\Annotations;

use Doctrine\Common\Annotations\Annotation;
use Robert430404\GuzzleFace\Annotations\Method\Contracts\MethodAnnotationInterface;
use Robert430404\GuzzleFace\Annotations\Request\Endpoint;

/**
 * Class Action
 *
 * @Annotation
 * @Annotation\Target({"METHOD"})
 *
 * @package Robert430404\GuzzleFace\Annotations
 */
class Action extends Annotation
{
    /**
     * @var MethodAnnotationInterface
     */
    public $method;

    /**
     * @var Endpoint
     */
    public $endpoint;

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method->getHttpMethod();
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint->getUri();
    }
}