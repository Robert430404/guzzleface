<?php

namespace Robert430404\GuzzleFace\Tests\Integration\Generated;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Robert430404\GuzzleFace\Tests\Integration\Fixtures\FixtureClientInterface;

class FixtureClient extends Client implements FixtureClientInterface
{
    public function __construct(array $config = [])
    {
        parent::__construct(array_merge(array(
            'base_uri' => 'http://localhost:8080',
            'api_name' => 'Fixture  Client',
            'auth' => array(
                'username' => 'test',
                'password' => 'test',
            ),
            'headers' => array(
                'Authorization' => 'Bearer test-token-here',
                'User-Agent' => 'roberts-test-agent',
                'Content-Type' => 'application/json',
                'x-test-header' => 'test-body',
                'x-test-header-2' => 'test-body',
            ),
        ), $config));
    }

    public function get() : Response
    {
        return $this->request('GET', "/resource", array(
            'headers' => array(
                array(
                    'username' => 'override',
                    'password' => 'override',
                ),
                array(
                    'x-override' => 'test',
                ),
            ),
        ));
    }

    public function json(string $body) : Response
    {
        return $this->request('POST', "/resource", array(
            'body' => $body,
            'headers' => array(
                array(
                    'Authorization' => 'Bearer robs-test-token',
                ),
                array(
                    'x-override' => 'test',
                ),
                array(
                    'User-Agent' => 'test-agent',
                ),
            ),
        ));
    }

    public function formParams(array $body) : Response
    {
        return $this->request('POST', "/resource", array(
            'form_params' => $body,
        ));
    }

    public function multiPart(array $body) : Response
    {
        return $this->request('POST', "/resource", array(
            'multipart' => $body,
        ));
    }

    public function delete(string $record) : Response
    {
        return $this->request('DELETE', "/resource/$record", array(
        ));
    }

    public function put(string $body, string $record, string $record2) : Response
    {
        return $this->request('PUT', "/resource/$record/resource2/$record2", array(
            'body' => $body,
        ));
    }

    public function patch(string $body, string $record) : Response
    {
        return $this->request('PATCH', "/resource/$record", array(
            'body' => $body,
        ));
    }

    public function options() : Response
    {
        return $this->request('OPTIONS', "/resource", array(
        ));
    }
}
