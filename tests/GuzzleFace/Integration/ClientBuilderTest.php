<?php

namespace Robert430404\GuzzleFace\Tests;

use Doctrine\Common\Annotations\{AnnotationReader, AnnotationRegistry};
use GuzzleHttp\ClientInterface;
use League\Flysystem\{Adapter\Local, Filesystem};
use Robert430404\GuzzleFace\ClientBuilder;
use Robert430404\GuzzleFace\Exceptions\InvalidClientInterfaceProvidedException;
use Robert430404\GuzzleFace\Tests\Integration\Fixtures\FixtureClientInterface;

/**
 * Class ClientBuilderTest
 *
 * @package Robert430404\GuzzleFace\Tests
 */
class ClientBuilderTest extends AbstractBaseTestCase
{
    /**
     * @var ClientBuilder
     */
    private $builder;

    /**
     * Sets up each test run.
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function setUp()
    {
        parent::setUp();

        // Enable Composer Autoloading Of Annotations While We Test
        AnnotationRegistry::registerLoader('class_exists');

        $fileSystem = new Filesystem(
            new Local(__DIR__ . '/Generated')
        );

        // Hand everything to the builder.
        $this->builder = new ClientBuilder(
            new AnnotationReader(), $fileSystem
        );
    }

    /**
     * @test
     *
     * @throws \ReflectionException
     * @throws \Robert430404\GuzzleFace\Exceptions\InvalidClientInterfaceProvidedException
     * @throws \Robert430404\GuzzleFace\Exceptions\NoBodyTypeProvidedException
     */
    public function shouldBuildAndInstantiateTheClient()
    {
        $client = $this
            ->builder
            ->buildClient(
                FixtureClientInterface::class,
                'Robert430404\\GuzzleFace\\Tests\\Integration\\Generated'
            );

        $this->assertInstanceOf(ClientInterface::class, $client);
        $this->assertInstanceOf(FixtureClientInterface::class, $client);
    }

    /**
     * @test
     *
     * @throws InvalidClientInterfaceProvidedException
     * @throws \ReflectionException
     * @throws \Robert430404\GuzzleFace\Exceptions\NoBodyTypeProvidedException
     */
    public function shouldGetInvalidClientInterfaceProvidedException()
    {
        $this->expectException(InvalidClientInterfaceProvidedException::class);

        $this->builder->buildClient('bad-interface', 'bad-namespace-destination');
    }
}