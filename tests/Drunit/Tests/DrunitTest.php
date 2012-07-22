<?php

/**
 * This file is part of Drunit
 *
 * (c) Korstiaan de Ridder <korstiaan@korstiaan.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Drunit\Tests;

use Drunit\Drunit;

class DrunitTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        Drunit::bootstrap();
        Drunit::enableModule(__DIR__.'/../../modules/drunit_test');
    }

    public function testBootstrapped()
    {
        $this->assertTrue(defined('DRUPAL_ROOT'));
        $this->assertSame(\DRUPAL_BOOTSTRAP_FULL, drupal_bootstrap());
    }

    public function testSingleModuleEnable()
    {
        $this->assertTrue(function_exists('drunit_test'));
    }

    public function testMultipleModuleEnable()
    {
        Drunit::enableModule(__DIR__.'/../../modules/multi', array(
            'drunit_test2',
            'drunit_test3',
        ));

        $this->assertTrue(function_exists('drunit_test2'));
        $this->assertTrue(function_exists('drunit_test3'));
    }
    
    public function testHooks()
    {
        $this->assertTrue(in_array('init',drunit_test_static()));
        $this->assertTrue(in_array('boot',drunit_test_static()));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testModuleException()
    {
        Drunit::enableModule(__DIR__.'/../../modules/foobar');
    }

}
