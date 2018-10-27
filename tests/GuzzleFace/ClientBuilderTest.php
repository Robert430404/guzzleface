<?php

namespace Robert430404\GuzzleFace\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use GuzzleHttp\ClientInterface;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Robert430404\GuzzleFace\ClientBuilder;
use Robert430404\GuzzleFace\ClientWriter;
use Robert430404\GuzzleFace\Tests\Fixtures\FixtureClientInterface;

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
     * @throws \Robert430404\GuzzleFace\Exceptions\InvalidClientInterfaceProvided
     */
    public function shouldBuildGuzzleClient()
    {
        $client = $this
            ->builder
            ->buildClient(
                FixtureClientInterface::class,
                'Robert430404\\GuzzleFace\\Tests\\Generated'
            );

        $this->assertInstanceOf(ClientInterface::class, $client);
        $this->assertInstanceOf(FixtureClientInterface::class, $client);
    }
}