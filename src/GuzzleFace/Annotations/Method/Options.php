<?php

namespace Robert430404\GuzzleFace\Annotations\Method;

use Doctrine\Common\Annotations\Annotation;
use Robert430404\GuzzleFace\Annotations\Method\Contracts\MethodAnnotationInterface;

/**
 * Class Options
 *
 * Exposes the OPTIONS http method through annotation.
 *
 * @Annotation
 * @Annotation\Target({"METHOD"})
 *
 * @package Robert430404\GuzzleFace\Annotations\Method
 */
class Options extends Annotation implements MethodAnnotationInterface
{
    /**
     * Returns the HTTP Method for the method annotation.
     *
     * @return string
     */
    public function getHttpMethod(): string
    {
        return 'OPTIONS';
    }
}