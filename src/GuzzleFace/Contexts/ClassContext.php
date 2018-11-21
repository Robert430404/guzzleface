<?php

namespace Robert430404\GuzzleFace\Contexts;

use GuzzleHttp\ClientInterface;

/**
 * Class ClassContext
 *
 * This handles splitting out all of the class information from the client name for the generated client.
 *
 * @package Robert430404\GuzzleFace\Contexts
 */
class ClassContext
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $interface;

    /**
     * @var string
     */
    private $classNameSpace;

    /**
     * @var string
     */
    private $clientName;

    /**
     * ClassContext constructor.
     *
     * @param string $clientName
     * @param string $clientNamespace
     */
    public function __construct(string $clientName, string $clientNamespace)
    {
        // Begin Parsing Client Name
        $nameParts = explode('\\', $clientName);

        // Split the name into the various parts of the class.
        $this->class = str_replace('Interface', '', end($nameParts));
        $this->interface = end($nameParts);
        $this->classNameSpace = "$clientNamespace\\$this->class";
        $this->clientName = $clientName;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getInterface(): string
    {
        return $this->interface;
    }

    /**
     * @return string
     */
    public function getClassNameSpace(): string
    {
        return $this->classNameSpace;
    }

    /**
     * @return string
     */
    public function getClientName(): string
    {
        return $this->clientName;
    }

    /**
     * @param array $config
     *
     * @return ClientInterface
     */
    public function getNewInstance(array $config = []): ClientInterface
    {
        return new $this->classNameSpace($config);
    }
}