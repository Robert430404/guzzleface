<?php

namespace Robert430404\GuzzleFace\Contracts;

/**
 * Interface ClientWriterInterface
 *
 * @package Robert430404\GuzzleFace\Contracts
 */
interface ClientWriterInterface
{
    /**
     * Sets the generation directory for the clients.
     *
     * @param string $generationDir
     *
     * @return ClientWriterInterface
     */
    public function setGenerationDir(string $generationDir): ClientWriterInterface;

    /**
     * Sets the namespace for the generated clients to use.
     *
     * @param string $namespace
     *
     * @return ClientWriterInterface
     */
    public function setGeneratedClientNamespace(string $namespace): ClientWriterInterface;

    /**
     * Sets the interface that we are going to be reflecting on to generate the client.
     *
     * @param string $interface
     *
     * @return ClientWriterInterface
     */
    public function setClientInterface(string $interface): ClientWriterInterface;

    /**
     * Writes the clients to the provided directory and returns the class namespace.
     *
     * @return string
     */
    public function writeClients(): string;
}