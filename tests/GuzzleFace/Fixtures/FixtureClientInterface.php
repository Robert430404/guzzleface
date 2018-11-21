<?php

namespace Robert430404\GuzzleFace\Tests\Fixtures;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Robert430404\GuzzleFace\Annotations\{Action,
    ApiName,
    BaseUrl,
    Method\Delete,
    Method\Get,
    Method\Patch,
    Method\Post,
    Method\Put,
    Request\Body,
    Request\Endpoint};

/**
 * Interface FixtureClientInterface
 *
 * @BaseUrl(host="http://localhost:8080/")
 * @ApiName(name="Fixture Client")
 *
 * @package Robert430404\GuzzleFace\Tests\Fixtures
 */
interface FixtureClientInterface extends ClientInterface
{
    /**
     * @Action(
     *     method=@Get,
     *     endpoint=@Endpoint(uri="/resource")
     * )
     *
     * @return Response
     */
    public function get(): Response;

    /**
     * @Action(
     *     method=@Post,
     *     endpoint=@Endpoint(uri="/resource"),
     *     body=@Body(json=true, name="body")
     * )
     *
     * @param string $body
     *
     * @return Response
     */
    public function json(string $body): Response;

    /**
     * @Action(
     *     method=@Post,
     *     endpoint=@Endpoint(uri="/resource"),
     *     body=@Body(formParams=true, name="body")
     * )
     *
     * @param array $body
     *
     * @return Response
     */
    public function formParams(array $body): Response;

    /**
     * @Action(
     *     method=@Post,
     *     endpoint=@Endpoint(uri="/resource"),
     *     body=@Body(multiPart=true, name="body")
     * )
     *
     * @param array $body
     *
     * @return Response
     */
    public function multiPart(array $body): Response;

    /**
     * @Action(
     *     method=@Delete,
     *     endpoint=@Endpoint(uri="/resource")
     * )
     *
     * @param string $record
     *
     * @return Response
     */
    public function delete(string $record): Response;

    /**
     * @Action(
     *     method=@Put,
     *     endpoint=@Endpoint(uri="/resource"),
     *     body=@Body(json=true, name="body")
     * )
     *
     * @param string $body
     *
     * @return Response
     */
    public function put(string $body): Response;

    /**
     * @Action(
     *     method=@Patch,
     *     endpoint=@Endpoint(uri="/resource"),
     *     body=@Body(json=true, name="body")
     * )
     *
     * @param string $body
     *
     * @return Response
     */
    public function patch(string $body): Response;
}