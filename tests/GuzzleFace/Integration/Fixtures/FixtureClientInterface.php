<?php

namespace Robert430404\GuzzleFace\Tests\Integration\Fixtures;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Robert430404\GuzzleFace\Annotations\{Action, ApiName, BaseUrl};
use Robert430404\GuzzleFace\Annotations\Method\{Delete, Get, Options, Patch, Post, Put};
use Robert430404\GuzzleFace\Annotations\Request\{Body, Endpoint, Headers};
use Robert430404\GuzzleFace\Annotations\Request\Headers\{AuthBasic, AuthBearer, ContentType, CustomHeader, UserAgent};

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
     * @Headers(headers={
     *     @AuthBearer(token="robs-test-token"),
     *     @CustomHeader(name="x-override", body="test"),
     *     @UserAgent(agent="test-agent")
     * })
     *
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
     *     endpoint=@Endpoint(uri="/resource/{record}")
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
     *     endpoint=@Endpoint(uri="/resource/{record}/resource2/{record2}"),
     *     body=@Body(json=true, name="body")
     * )
     *
     * @param string $body
     * @param string $record
     * @param string $record2
     *
     * @return Response
     */
    public function put(string $body, string $record, string $record2): Response;

    /**
     * @Action(
     *     method=@Patch,
     *     endpoint=@Endpoint(uri="/resource/{record}"),
     *     body=@Body(json=true, name="body")
     * )
     *
     * @param string $body
     * @param string $record
     *
     * @return Response
     */
    public function patch(string $body, string $record): Response;

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