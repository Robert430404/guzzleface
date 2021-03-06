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
     * @var bool
     */
    private $cache = false;

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
     * Toggles cache functionality.
     *
     * @param bool $cache
     *
     * @return ClientBuilder
     */
    public function cache(bool $cache): ClientBuilder
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * Add a new client to the builder.
     *
     * @param string $clientName
     * @param string $clientNamespace
     *
     * @param array $configOverrides
     * @return ClientInterface
     *
     * @throws InvalidClientInterfaceProvidedException
     * @throws NoBodyTypeProvidedException
     * @throws ReflectionException
     */
    public function buildClient(
        string $clientName,
        string $clientNamespace,
        array $configOverrides = []
    ): ClientInterface {
        if (!interface_exists($clientName)) {
            throw new InvalidClientInterfaceProvidedException(
                "You did not provide an auto loaded client interface: {$clientName}."
            );
        }

        $classContext = new ClassContext($clientName, $clientNamespace);

        if ($this->shouldPullFromCache($classContext)) {
            return $classContext->getNewInstance($configOverrides);
        }

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
            $clientName
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

        return $classContext->getNewInstance($configOverrides);
    }

    /**
     * Checks to see if we can serve the client from cache and not regenerate it.
     *
     * @param ClassContext $context
     *
     * @return bool
     */
    private function shouldPullFromCache(ClassContext $context): bool
    {
        if (!$this->cache) {
            return false;
        }

        if ($this->clientWriter->has("{$context->getClass()}.php")) {
            return true;
        }

        return false;
    }
}