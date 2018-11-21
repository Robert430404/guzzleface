<?php

namespace Robert430404\GuzzleFace\Factories;

use Memio\Model\Contract;
use Memio\Model\File;
use Memio\Model\FullyQualifiedName;
use Memio\Model\Method;
use Memio\Model\Objekt;

/**
 * Class FileFactory
 *
 * @package Robert430404\GuzzleFace\Factories
 */
class FileFactory
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var null|string
     */
    private $parent;

    /**
     * @var string[]
     */
    private $implements = [];

    /**
     * @var string[]
     */
    private $imports = [];

    /**
     * @var Method[]
     */
    private $methods = [];

    /**
     * @var string
     */
    private $fullyQualifiedNameSpace;

    /**
     * FileFactory constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $parent
     *
     * @return FileFactory
     */
    public function setParent(string $parent): FileFactory
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @param array $interfaces
     *
     * @return FileFactory
     */
    public function setInterfaces(array $interfaces): FileFactory
    {
        $this->implements = $interfaces;

        return $this;
    }

    /**
     * @param array $imports
     *
     * @return FileFactory
     */
    public function setImports(array $imports): FileFactory
    {
        $this->imports = $imports;

        return $this;
    }

    /**
     * @param array $methods
     *
     * @return FileFactory
     */
    public function setMethods(array $methods): FileFactory
    {
        $this->methods = $methods;

        return $this;
    }

    /**
     * @param string $namespace
     *
     * @return FileFactory
     */
    public function setFullyQualifiedNameSpace(string $namespace): FileFactory
    {
        $this->fullyQualifiedNameSpace = $namespace;

        return $this;
    }

    /**
     * @return File
     */
    public function makeFile(): File
    {
        $file = new File($this->name);
        $client = (new Objekt($this->fullyQualifiedNameSpace))->extend(new Objekt($this->parent));

        foreach ($this->implements as $interface) {
            $client->implement(
                new Contract($interface)
            );
        }

        foreach ($this->methods as $method) {
            $client->addMethod(
                $method
            );
        }

        foreach ($this->imports as $import) {
            $file->addFullyQualifiedName(
                new FullyQualifiedName($import)
            );
        }

        return $file->setStructure($client);
    }
}