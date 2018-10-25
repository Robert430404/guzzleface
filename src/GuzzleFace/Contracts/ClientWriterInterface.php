<?php

namespace Robert430404\GuzzleFace\Contracts;

/**
 * Interface ClientWriterInterface
 *
 * @package Robert430404\GuzzleFace\Contracts
 */
interface ClientWriterInterface
{
    public function setGenerationDir(string $generationDir): ClientWriterInterface;

    public function setInterface(string $interface): ClientWriterInterface;

    /**
     * Writes the clients to the provided directory.
     *
     * @return void
     */
    public function writeClients(): void;
}