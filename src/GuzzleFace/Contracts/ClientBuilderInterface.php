<?php

namespace Robert430404\GuzzleFace\Contracts;

use GuzzleHttp\ClientInterface;
use ReflectionException;
use Robert430404\GuzzleFace\Exceptions\InvalidClientInterfaceProvidedException;
use Robert430404\GuzzleFace\Exceptions\NoBodyTypeProvidedException;

/**
 * Interface ClientBuilderInterface
 *
 * @package Robert430404\GuzzleFace\Contracts
 */
interface ClientBuilderInterface
{
    /**
     * Add a new client to the builder.
     *
     * @param string $clientName
     * @param string $clientNamespace
     *
     * @return ClientInterface
     *
     * @throws InvalidClientInterfaceProvidedException
     * @throws ReflectionException
     * @throws NoBodyTypeProvidedException
     */
    public function buildClient(string $clientName, string $clientNamespace): ClientInterface;
}