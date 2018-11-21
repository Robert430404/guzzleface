<?php

namespace Robert430404\GuzzleFace\Tests\Generated;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Robert430404\GuzzleFace\Tests\Fixtures\FixtureClientInterface;

class FixtureClient extends Client implements FixtureClientInterface
{
    public function __construct(array $config = [])
    {
parent::__construct($config);
    }

    public function get() : Response
    {
return $this->request('GET', '/resource', array());
    }

    public function json(string $body) : Response
    {
return $this->request('POST', '/resource', array('body'=>$body,));
    }

    public function formParams(array $body) : Response
    {
return $this->request('POST', '/resource', array('form_params'=>$body,));
    }

    public function multiPart(array $body) : Response
    {
return $this->request('POST', '/resource', array('multipart'=>$body,));
    }

    public function delete(string $record) : Response
    {
return $this->request('DELETE', '/resource', array());
    }

    public function put(string $body) : Response
    {
return $this->request('PUT', '/resource', array('body'=>$body,));
    }

    public function patch(string $body) : Response
    {
return $this->request('PATCH', '/resource', array('body'=>$body,));
    }
}
