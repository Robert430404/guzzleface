<?php

namespace Robert430404\GuzzleFace\Annotations;

use Doctrine\Common\Annotations\Annotation;
use Robert430404\GuzzleFace\Annotations\Method\Contracts\MethodAnnotationInterface;
use Robert430404\GuzzleFace\Annotations\Request\Body;
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
     * @var Body
     */
    public $body;

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

    /**
     * @return bool
     */
    public function hasBody(): bool
    {
        return $this->body !== null;
    }

    /**
     * @return string
     *
     * @throws \Robert430404\GuzzleFace\Exceptions\NoBodyTypeProvidedException
     */
    public function getBodyType(): string
    {
        return $this->body->getBodyType();
    }

    /**
     * @return string
     */
    public function getBodyParamName(): string
    {
        return $this->body->getName();
    }
}