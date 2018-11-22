<?php

namespace Robert430404\GuzzleFace\Tests\Unit\Annotations\Requests;

use Robert430404\GuzzleFace\Annotations\Request\Body;
use Robert430404\GuzzleFace\Exceptions\NoBodyTypeProvidedException;
use Robert430404\GuzzleFace\Tests\AbstractBaseTestCase;

/**
 * Class BodyTest
 *
 * @package Robert430404\GuzzleFace\Tests\Unit\Annotations\Requests
 */
class BodyTest extends AbstractBaseTestCase
{
    /**
     * @test
     *
     * @throws NoBodyTypeProvidedException
     */
    public function shouldGetNoBodyTypeProvidedException()
    {
        $this->expectException(NoBodyTypeProvidedException::class);

        (new Body([]))->getBodyType();
    }
}