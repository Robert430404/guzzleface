<?php

namespace Robert430404\GuzzleFace;

use Doctrine\Common\Annotations\Reader;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Robert430404\GuzzleFace\Annotations\Contracts\ConfigurationAnnotationInterface;
use Robert430404\GuzzleFace\Contracts\ClientWriterInterface;

/**
 * Class ClientBuilder
 *
 * Builds the client from the annotations on your class.
 *
 * @package Robert430404\GuzzleFace
 */
class ClientBuilder
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var ClientWriterInterface
     */
    private $clientWriter;

    /**
     * ClientBuilder constructor.
     *
     * @param Reader $reader
     * @param ClientWriterInterface $clientWriter
     */
    public function __construct(
        Reader $reader,
        ClientWriterInterface $clientWriter
    ) {
        $this->reader = $reader;
        $this->clientWriter = $clientWriter;
    }

    /**
     * Add a new client to the builder.
     *
     * @param string $clientName
     *
     * @return ClientInterface
     *
     * @throws \ReflectionException
     */
    public function buildClient(string $clientName)
    {
        $reflectionInstance = new \ReflectionClass($clientName);
        $classAnnotations = $this
            ->reader
            ->getClassAnnotations($reflectionInstance);

        $clientConfig = [];

        /**
         * Load up the global configuration from the class annotations.
         *
         * @var ConfigurationAnnotationInterface $annotation
         */
        foreach ($classAnnotations as $annotation) {
            $clientConfig[$annotation->getConfigKey()] = $annotation->getValue();
        }

        return new Client($clientConfig);
    }
}