<?php

namespace Robert430404\GuzzleFace\Tests\Unit\Contexts;

use Robert430404\GuzzleFace\Contexts\ClassContext;
use Robert430404\GuzzleFace\Tests\AbstractBaseTestCase;
use Robert430404\GuzzleFace\Tests\Integration\Fixtures\FixtureClientInterface;

/**
 * Class ClassContextTest
 *
 * @package Robert430404\GuzzleFace\Tests\Unit\Contexts
 */
class ClassContextTest extends AbstractBaseTestCase
{
    /**
     * @test
     */
    public function shouldParseInterfaceNameProperly()
    {
        $context = new ClassContext(
            FixtureClientInterface::class,
            'Robert430404\\GuzzleFace\\Tests\\Integration\\Generated'
        );

        $this->assertEquals('FixtureClientInterface', $context->getInterface());
    }

    /**
     * @test
     */
    public function shouldParseClassNameProperly()
    {
        $context = new ClassContext(
            FixtureClientInterface::class,
            'Robert430404\\GuzzleFace\\Tests\\Integration\\Generated'
        );

        $this->assertEquals('FixtureClient', $context->getClass());
    }

    /**
     * @test
     */
    public function shouldParseClassNameSpaceProperly()
    {
        $context = new ClassContext(
            FixtureClientInterface::class,
            'Robert430404\\GuzzleFace\\Tests\\Integration\\Generated'
        );

        $this->assertEquals(
            'Robert430404\\GuzzleFace\\Tests\\Integration\\Generated\\FixtureClient',
            $context->getClassNameSpace()
        );
    }
}