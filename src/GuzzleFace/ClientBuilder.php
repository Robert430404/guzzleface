<?php

namespace Robert430404\GuzzleFace;

use ReflectionClass;
use ReflectionException;
use Doctrine\Common\Annotations\Reader;
use League\Flysystem\FilesystemInterface;
use Memio\Memio\Config\Build;
use GuzzleHttp\{Client, ClientInterface, Psr7\Response};
use Robert430404\GuzzleFace\Contexts\ClassContext;
use Robert430404\GuzzleFace\Exceptions\{InvalidClientInterfaceProvidedException, NoBodyTypeProvidedException};
use Robert430404\GuzzleFace\Factories\{ConfigFactory, FileFactory, MethodFactory};

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
     * @var array
     */
    private $configOverrides;

    /**
     * ClientBuilder constructor.
     *
     * @param Reader $reader
     * @param FilesystemInterface $clientWriter
     * @param array $configOverrides
     */
    public function __construct(
        Reader $reader,
        FilesystemInterface $clientWriter,
        array $configOverrides = []
    ) {
        $this->reader = $reader;
        $this->clientWriter = $clientWriter;
        $this->configOverrides = $configOverrides;
    }

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
    public function buildClient(string $clientName, string $clientNamespace): ClientInterface
    {
        if (!interface_exists($clientName)) {
            throw new InvalidClientInterfaceProvidedException(
                "You did not provide an auto loaded client interface: {$clientName}."
            );
        }

        $classContext = new ClassContext($clientName, $clientNamespace);
        $classAnnotations = $this->reader->getClassAnnotations(
            $reflectionInstance = new ReflectionClass(
                $classContext->getClientName()
            )
        );

        $clientConfig = (new ConfigFactory($classAnnotations))->makeConfig();
        $validMethods = array_filter(
            $reflectionInstance->getMethods(),
            function (\ReflectionMethod $method) use ($clientName) {
                return $method->class === $clientName;
            }
        );

        $methodAnnotations = array_map(
            function (\ReflectionMethod $method) {
                return $this->reader->getMethodAnnotations($method);
            },
            $validMethods
        );

        $imports = [
            Client::class,
            Response::class,
            $clientName,
        ];

        $implements = [
            $clientName,
        ];

        $methods = (new MethodFactory($validMethods, $methodAnnotations, $clientConfig))
            ->makeMethods();

        $file = (new FileFactory($classContext->getClass()))
            ->setImports($imports)
            ->setParent(Client::class)
            ->setInterfaces($implements)
            ->setMethods($methods)
            ->setFullyQualifiedNameSpace($classContext->getClassNameSpace())
            ->makeFile();

        $this
            ->clientWriter
            ->put(
                "{$classContext->getClass()}.php",
                Build::prettyPrinter()->generateCode($file)
            );

        return $classContext->getNewInstance($this->configOverrides);
    }
}