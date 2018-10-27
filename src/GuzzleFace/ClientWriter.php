<?php

namespace Robert430404\GuzzleFace;

use GuzzleHttp\Client;
use Memio\Memio\Config\Build;
use Memio\Model\Contract;
use Memio\Model\File;
use Memio\Model\FullyQualifiedName;
use Memio\Model\Method;
use Memio\Model\Objekt;
use Memio\Model\Property;
use Robert430404\GuzzleFace\Contracts\ClientWriterInterface;
use Robert430404\GuzzleFace\Exceptions\InvalidClientInterfaceProvided;
use Robert430404\GuzzleFace\Exceptions\NoClientInterfaceException;
use Robert430404\GuzzleFace\Exceptions\NoClientNameSpaceSetException;
use Robert430404\GuzzleFace\Exceptions\NoGenerationDirectorySetException;

/**
 * Class ClientWriter
 *
 * @package Robert430404\GuzzleFace
 */
class ClientWriter implements ClientWriterInterface
{
    /**
     * @var string
     */
    private $generationDir;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $interface;

    /**
     * Sets the generation directory for the clients.
     *
     * @param string $generationDir
     *
     * @return ClientWriterInterface
     */
    public function setGenerationDir(string $generationDir): ClientWriterInterface
    {
        $this->generationDir = $generationDir;

        return $this;
    }

    /**
     * Sets the namespace for the generated clients to use.
     *
     * @param string $namespace
     *
     * @return ClientWriterInterface
     */
    public function setGeneratedClientNamespace(string $namespace): ClientWriterInterface
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * Sets the interface we are using for generation.
     *
     * @param string $interface
     *
     * @return ClientWriterInterface
     */
    public function setClientInterface(string $interface): ClientWriterInterface
    {
        $this->interface = $interface;

        return $this;
    }

    /**
     * Writes the clients to the provided directory.
     *
     * @return string
     *
     * @throws NoGenerationDirectorySetException
     * @throws NoClientNameSpaceSetException
     * @throws NoClientInterfaceException
     * @throws InvalidClientInterfaceProvided
     */
    public function writeClients(): string
    {
        $this->validateState();

        $nameParts = explode('\\', $this->interface);
        $className = str_replace('Interface', '', end($nameParts));
        $classFqns = "$this->namespace\\$className";
        $filePath = "{$this->generationDir}/"
            . str_replace('Interface', '', $className)
            . '.php';

        $code = $this->generateClientCode($classFqns, $filePath);

        file_put_contents($filePath, $code);

        return $classFqns;
    }

    /**
     * Returns the markup for the code generated client.
     *
     * @param string $className
     * @param string $filePath
     *
     * @return string
     */
    private function generateClientCode(string $className, string $filePath): string
    {
        $guzzle = new Objekt(Client::class);
        $interface = new Contract($this->interface);
        $file = (new File($filePath))
            ->setStructure(
                (new Objekt($className))
                    ->extend($guzzle)
                    ->implement($interface)
                    ->addProperty(
                        new Property('test')
                    )
                    ->addMethod(
                        (new Method('__construct'))
                    )
                    ->addMethod(
                        new Method('test')
                    )
            )
            ->addFullyQualifiedName(
                new FullyQualifiedName(
                    $guzzle->getFullyQualifiedName()
                )
            )
            ->addFullyQualifiedName(
                new FullyQualifiedName(
                    $interface->getFullyQualifiedName()
                )
            );

        return Build::prettyPrinter()->generateCode($file);
    }
}