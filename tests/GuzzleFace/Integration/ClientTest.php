<?php

namespace Robert430404\GuzzleFace\Tests\Integration;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Robert430404\GuzzleFace\ClientBuilder;
use Robert430404\GuzzleFace\Exceptions\InvalidClientInterfaceProvidedException;
use Robert430404\GuzzleFace\Exceptions\NoBodyTypeProvidedException;
use Robert430404\GuzzleFace\Tests\AbstractBaseTestCase;
use Robert430404\GuzzleFace\Tests\Integration\Fixtures\FixtureClientInterface;
use Robert430404\GuzzleFace\Tests\Integration\Generated\FixtureClient;

/**
 * Class ClientTest
 *
 * This contains basic tests that ensure that the generated client does what it is expected to do.
 *
 * @package Robert430404\GuzzleFace\Tests\Integration
 */
class ClientTest extends AbstractBaseTestCase
{
    /**
     * @var FixtureClient
     */
    private $client;

    /**
     * Sets up each test run.
     *
     * @throws AnnotationException
     * @throws \ReflectionException
     * @throws InvalidClientInterfaceProvidedException
     * @throws NoBodyTypeProvidedException
     */
    public function setUp()
    {
        parent::setUp();

        $this->filesystem = $fileSystem = new Filesystem(
            new Local(__DIR__ . '/Generated')
        );

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar']),
        ]);

        $handler = HandlerStack::create($mock);

        // Hand everything to the builder.
        $builder = new ClientBuilder(new AnnotationReader(), $fileSystem);

        $this->client = $builder
            ->cache(true)
            ->buildClient(
                FixtureClientInterface::class,
                'Robert430404\\GuzzleFace\\Tests\\Integration\\Generated',
                [
                    'handler' => $handler
                ]
            );
    }

    /**
     * @test
     *
     * @throws GuzzleException
     */
    public function shouldGetBack200()
    {
        $response = $this->client->get();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     *
     * @throws GuzzleException
     */
    public function shouldPost200()
    {
        $response = $this->client->json(json_encode(['test' => 'test']));

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     *
     * @throws GuzzleException
     */
    public function shouldPut200()
    {
        $response = $this->client->put(json_encode(['test' => 'test']), '1', '1');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     *
     * @throws GuzzleException
     */
    public function shouldPatch200()
    {
        $response = $this->client->patch(json_encode(['test' => 'test']), '1');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     *
     * @throws GuzzleException
     */
    public function shouldPost200WithFormParams()
    {
        $response = $this->client->formParams(['test' => 'test']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     *
     * @throws GuzzleException
     */
    public function shouldPost200WithMultiPart()
    {
        $response = $this->client->multiPart([
            [
                'contents' => 'test',
                'name' => 'test',
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     *
     * @throws GuzzleException
     */
    public function shouldDelete200()
    {
        $response = $this->client->delete('1');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     *
     * @throws GuzzleException
     */
    public function shouldOptions200()
    {
        $response = $this->client->options();

        $this->assertEquals(200, $response->getStatusCode());
    }
}