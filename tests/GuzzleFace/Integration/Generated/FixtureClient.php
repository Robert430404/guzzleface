<?php

namespace Robert430404\GuzzleFace\Tests\Integration\Generated;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Robert430404\GuzzleFace\Tests\Integration\Fixtures\FixtureClientInterface;

/**
 * Code Generated By Guzzleface
 *
 * DO NOT MODIFY DIRECTLY, ANNOTATE INTERFACE
 *
 * @package Robert430404\GuzzleFace\Tests\Integration\Generated\FixtureClient
 */
class FixtureClient extends Client implements FixtureClientInterface
{
    /**
     * Creates new client instance.
     *
     * @param array $config client configuration
     */
    public function __construct(array $config = [])
    {
        parent::__construct(array_merge(array(
            'base_uri' => 'http://localhost:8080',
            'api_name' => 'Fixture  Client',
            'auth' => array(
                'test',
                'test',
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

    /**
     * Performs the get call
     *
     * @return \GuzzleHttp\Psr7\Response
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get() : Response
    {
        return $this->request('GET', "/resource", array(
            'headers' => array(
                'x-override' => 'test',
                'Authorization' => 'Basic b3ZlcnJpZGU6b3ZlcnJpZGU=',
            ),
        ));
    }

    /**
     * Performs the json call
     *
     * @param string $body
     *
     * @return \GuzzleHttp\Psr7\Response
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function json(string $body) : Response
    {
        return $this->request('POST', "/resource", array(
            'body' => $body,
            'headers' => array(
                'User-Agent' => 'test-agent',
                'x-override' => 'test',
                'Authorization' => 'Bearer robs-test-token',
            ),
        ));
    }

    /**
     * Performs the formParams call
     *
     * @param array $body
     *
     * @return \GuzzleHttp\Psr7\Response
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function formParams(array $body) : Response
    {
        return $this->request('POST', "/resource", array(
            'form_params' => $body,
        ));
    }

    /**
     * Performs the multiPart call
     *
     * @param array $body
     *
     * @return \GuzzleHttp\Psr7\Response
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function multiPart(array $body) : Response
    {
        return $this->request('POST', "/resource", array(
            'multipart' => $body,
        ));
    }

    /**
     * Performs the delete call
     *
     * @param string $record
     *
     * @return \GuzzleHttp\Psr7\Response
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(string $record) : Response
    {
        return $this->request('DELETE', "/resource/$record", array(
        ));
    }

    /**
     * Performs the put call
     *
     * @param string $body
     * @param string $record
     * @param string $record2
     *
     * @return \GuzzleHttp\Psr7\Response
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function put(string $body, string $record, string $record2) : Response
    {
        return $this->request('PUT', "/resource/$record/resource2/$record2", array(
            'body' => $body,
        ));
    }

    /**
     * Performs the patch call
     *
     * @param string $body
     * @param string $record
     *
     * @return \GuzzleHttp\Psr7\Response
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function patch(string $body, string $record) : Response
    {
        return $this->request('PATCH', "/resource/$record", array(
            'body' => $body,
        ));
    }

    /**
     * Performs the options call
     *
     * @return \GuzzleHttp\Psr7\Response
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function options() : Response
    {
        return $this->request('OPTIONS', "/resource", array(
        ));
    }
}
