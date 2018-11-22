<?php

namespace Robert430404\GuzzleFace\Tests;

use Doctrine\Common\Annotations\AnnotationRegistry;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractBaseTestCase
 *
 * @package Robert430404\GuzzleFace\Tests
 */
abstract class AbstractBaseTestCase extends TestCase
{
    /**
     * Sets up each test run.
     */
    public function setUp()
    {
        parent::setUp();

        // Enable Composer Autoloading Of Annotations While We Test
        AnnotationRegistry::registerLoader('class_exists');
    }
}