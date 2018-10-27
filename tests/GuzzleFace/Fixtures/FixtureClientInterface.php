<?php

namespace Robert430404\GuzzleFace\Tests\Fixtures;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Robert430404\GuzzleFace\Annotations\{
    Action, ApiName, BaseUrl, Method\Get, Request\Endpoint
};

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
    public function get();
}