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
        // Replace placeholders with proper variables for method body.
        $parsedUri = preg_replace('/({)([a-zA-Z1-9]*)(})/', '$' . '$2', $this->uri);

        return $parsedUri;
    }
}