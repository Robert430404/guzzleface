<?php

namespace Robert430404\GuzzleFace\Annotations\Request\Headers;

use Doctrine\Common\Annotations\Annotation;
use Robert430404\GuzzleFace\Annotations\Contracts\ConfigurationAnnotationInterface;
use Robert430404\GuzzleFace\Enumerations\ConfigurationEnumerations;

/**
 * Class AuthBasic
 *
 * @Annotation
 * @Annotation\Target({"METHOD", "CLASS"})
 *
 * @package Robert430404\GuzzleFace\Annotations\Request\Headers
 */
class AuthBasic extends Annotation implements ConfigurationAnnotationInterface
{
    /**
     * @Annotation\Required
     *
     * @var string
     */
    public $username;

    /**
     * @Annotation\Required
     *
     * @var string
     */
    public $password;

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Returns the value that the configuration holds.
     *
     * @return array
     */
    public function getValue()
    {
        return [
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
        ];
    }

    /**
     * Returns the corresponding guzzle configuration key.
     *
     * @return string
     */
    public function getConfigKey(): string
    {
        return ConfigurationEnumerations::AUTH_CONFIG_KEY;
    }
}