<?php

namespace Robert430404\GuzzleFace\Annotations\Request;

use Doctrine\Common\Annotations\Annotation;
use Robert430404\GuzzleFace\Enumerations\BodyEnumerations;
use Robert430404\GuzzleFace\Exceptions\NoBodyTypeProvidedException;

/**
 * Class Body
 *
 * @Annotation
 * @Annotation\Target({"METHOD"})
 *
 * @package Robert430404\GuzzleFace\Annotations\Request
 */
class Body extends Annotation
{
    /**
     * @Annotation\Required
     *
     * @var string
     */
    public $name;

    /**
     * @var bool
     */
    public $json = false;

    /**
     * @var bool
     */
    public $multiPart = false;

    /**
     * @var bool
     */
    public $formParams = false;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isJson(): bool
    {
        return $this->json;
    }

    /**
     * @return bool
     */
    public function isFormParams(): bool
    {
        return $this->formParams;
    }

    /**
     * @return bool
     */
    public function isMultiPart(): bool
    {
        return $this->multiPart;
    }

    /**
     * @return string
     *
     * @throws NoBodyTypeProvidedException
     */
    public function getBodyType(): string
    {
        switch (true) {
            case $this->isJson() :
                return BodyEnumerations::JSON_BODY;
            case $this->isFormParams() :
                return BodyEnumerations::FORM_PARAMS_BODY;
            case $this->isMultiPart() :
                return BodyEnumerations::MULTI_PART_BODY;
            default :
                throw new NoBodyTypeProvidedException("Please provide a type for the body annotation.");
        }
    }
}