<?php

namespace Robert430404\GuzzleFace\Tests\Generated;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Robert430404\GuzzleFace\Tests\Fixtures\FixtureClientInterface;

class FixtureClient extends Client implements FixtureClientInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        return $this->send(
            new Request(
                'GET', 
                '/resource'
            )
        );
    }
}
