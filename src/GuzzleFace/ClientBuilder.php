<?php

namespace Robert430404\GuzzleFace;

use Doctrine\Common\Annotations\Reader;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use League\Flysystem\FilesystemInterface;
use Memio\Memio\Config\Build;
use Memio\Model\Contract;
use Memio\Model\File;
use Memio\Model\FullyQualifiedName;
use Memio\Model\Method;
use Memio\Model\Objekt;
use Memio\Model\Property;
use ReflectionClass;
use ReflectionException;
use Robert430404\GuzzleFace\Annotations\Action;
use Robert430404\GuzzleFace\Annotations\Contracts\ConfigurationAnnotationInterface;
use Robert430404\GuzzleFace\Exceptions\InvalidClientInterfaceProvided;

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
     * @var FilesystemInterface
     */
    private $clientWriter;

    /**
     * ClientBuilder constructor.
     *
     * @param Reader $reader
     * @param FilesystemInterface $clientWriter
     */
    public function __construct(
        Reader $reader,
        FilesystemInterface $clientWriter
    ) {
        $this->reader = $reader;
        $this->clientWriter = $clientWriter;
    }

    /**
     * Add a new client to the builder.
     *
     * @param string $clientName
     * @param string $clientNamespace
     *
     * @return ClientInterface
     *
     * @throws InvalidClientInterfaceProvided
     * @throws ReflectionException
     */
    public function buildClient(string $clientName, string $clientNamespace): ClientInterface
    {
        if (!interface_exists($clientName)) {
            throw new InvalidClientInterfaceProvided(
                "You did not provide an auto loaded client interface: {$clientName}."
            );
        }

        $nameParts = explode('\\', $clientName);
        $className = str_replace('Interface', '', end($nameParts));
        $classFqns = "$clientNamespace\\$className";

        $reflectionInstance = new ReflectionClass($clientName);
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

        $validMethods = array_filter(
            $reflectionInstance->getMethods(),
            function (\ReflectionMethod $method) use ($clientName) {
                return $method->class === $clientName;
            }
        );

        $methods = $this->resolveMethods($validMethods);

        $file = $this->buildFile(
            $clientName,
            $className,
            $classFqns,
            $methods
        );

        $this
            ->clientWriter
            ->put(
                $className . '.php',
                Build::prettyPrinter()->generateCode($file)
            );

        return new $classFqns($clientConfig);
    }

    /**
     * Builds the file for writing.
     *
     * @param string $clientName
     * @param string $className
     * @param string $classFqns
     * @param Method[] $methods
     *
     * @return File
     */
    private function buildFile(
        string $clientName,
        string $className,
        string $classFqns,
        array $methods
    ): File {
        $guzzle = new Objekt(Client::class);
        $request = new Objekt(Request::class);
        $response = new Objekt(Response::class);
        $interface = new Contract($clientName);
        $constructor = (new Method('__construct'))->setBody(
            "        parent::__construct();"
        );

        $generatedClientClass = (new Objekt($classFqns))
            ->extend($guzzle)
            ->implement($interface)
            ->addMethod($constructor);

        foreach ($methods as $method) {
            $generatedClientClass->addMethod($method);
        }

        return (new File($className))->setStructure(
            $generatedClientClass
        )->addFullyQualifiedName(
            new FullyQualifiedName(
                $guzzle->getFullyQualifiedName()
            )
        )->addFullyQualifiedName(
            new FullyQualifiedName(
                $request->getFullyQualifiedName()
            )
        )->addFullyQualifiedName(
            new FullyQualifiedName(
                $response->getFullyQualifiedName()
            )
        )->addFullyQualifiedName(
            new FullyQualifiedName(
                $interface->getFullyQualifiedName()
            )
        );
    }

    /**
     * @param \ReflectionMethod[] $reflectionMethods
     *
     * @return Method[]
     */
    private function resolveMethods(array $reflectionMethods): array
    {
        foreach ($reflectionMethods as $reflectionMethod) {
            $name = $reflectionMethod->getName();
            $annotations = $this
                ->reader
                ->getMethodAnnotations(
                    $reflectionMethod
                );

            /** @var Action $action */
            $action = array_reduce($annotations, function ($carry, $annotation) {
                if ($annotation instanceof Action) {
                    return $annotation;
                }

                return $carry;
            });

            $methods[] = (new Method($name))->setBody(<<<EOT
        return \$this->send(
            new Request(
                '{$action->getMethod()}', 
                '{$action->getEndpoint()}'
            )
        );
EOT
            );
        }

        return $methods ?? [];
    }
}