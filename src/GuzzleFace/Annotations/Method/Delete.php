<?php

namespace Robert430404\GuzzleFace\Annotations\Method;

use Doctrine\Common\Annotations\Annotation;
use Robert430404\GuzzleFace\Annotations\Method\Contracts\MethodAnnotationInterface;
use Robert430404\GuzzleFace\Enumerations\MethodEnumerations;

/**
 * Class Delete
 *
 * Exposes the DELETE http method through annotation.
 *
 * @Annotation
 * @Annotation\Target({"METHOD"})
 *
 * @package Robert430404\GuzzleFace\Annotations\Method
 */
class Delete extends Annotation implements MethodAnnotationInterface
{
    /**
     * Returns the HTTP Method for the method annotation.
     *
     * @return string
     */
    public function getHttpMethod(): string
    {
        return MethodEnumerations::HTTP_DELETE;
    }
}