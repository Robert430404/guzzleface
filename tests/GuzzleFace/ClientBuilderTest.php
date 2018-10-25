<?php

namespace Robert430404\GuzzleFace\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
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

        $this->builder = new ClientBuilder(
            new AnnotationReader(),
            new ClientWriter()
        );
    }

    /**
     * @test
     *
     * @throws \ReflectionException
     */
    public function shouldBuildGuzzleClient()
    {
        $this->builder->buildClient(FixtureClientInterface::class);
    }
}