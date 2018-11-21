<?php

namespace Robert430404\GuzzleFace\Tests\Fixtures;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Robert430404\GuzzleFace\Annotations\Action;
use Robert430404\GuzzleFace\Annotations\ApiName;
use Robert430404\GuzzleFace\Annotations\BaseUrl;
use Robert430404\GuzzleFace\Annotations\Method\{Delete, Get, Options, Patch, Post, Put};
use Robert430404\GuzzleFace\Annotations\Request\{Body,
    Endpoint,
    Headers,
    Headers\AuthBasic,
    Headers\AuthBearer,
    Headers\ContentType,
    Headers\CustomHeader,
    Headers\UserAgent};

/**
 * Interface FixtureClientInterface
 *
 * @BaseUrl(host="http://localhost:8080")
 * @ApiName(name="Fixture Client")
 * @AuthBasic(username="test", password="test")
 * @AuthBearer(token="test-token-here")
 * @UserAgent(agent="roberts-test-agent")
 * @ContentType(type="application/json")
 * @CustomHeader(name="x-test-header", body="test-body")
 * @CustomHeader(name="x-test-header-2", body="test-body")
 *
 * @package Robert430404\GuzzleFace\Tests\Fixtures
 */
interface FixtureClientInterface extends ClientInterface
{
    /**
     * @Headers(headers={
     *     @AuthBasic(username="override", password="override"),
     *     @CustomHeader(name="x-override", body="test")
     * })
     *
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

    /**
     * @Action(
     *     method=@Options,
     *     endpoint=@Endpoint(uri="/resource")
     * )
     *
     * @return Response
     */
    public function options(): Response;
}