<?php

namespace Robert430404\GuzzleFace\Annotations\Method\Contracts;

/**
 * Interface MethodAnnotationInterface
 *
 * Defines the contract for the method annotations.
 *
 * @package Robert430404\GuzzleFace\Annotations\Method\Contracts
 */
interface MethodAnnotationInterface
{
    /**
     * Returns the HTTP Method for the method annotation.
     *
     * @return string
     */
    public function getHttpMethod(): string;
}