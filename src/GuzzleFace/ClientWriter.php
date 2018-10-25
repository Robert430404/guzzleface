<?php

namespace Robert430404\GuzzleFace;

use Robert430404\GuzzleFace\Contracts\ClientWriterInterface;

/**
 * Class ClientWriter
 *
 * @package Robert430404\GuzzleFace
 */
class ClientWriter implements ClientWriterInterface
{
    public function setGenerationDir(string $generationDir): ClientWriterInterface
    {
        return $this;
    }

    public function setInterface(string $interface): ClientWriterInterface
    {
        return $this;
    }

    /**
     * Writes the clients to the provided directory.
     *
     * @return void
     */
    public function writeClients(): void
    {

    }
}