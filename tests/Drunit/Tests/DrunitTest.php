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
    }

    public function testBootstrapped()
    {
        $this->assertTrue(defined('DRUPAL_ROOT'));
        $this->assertSame(\DRUPAL_BOOTSTRAP_FULL, drupal_bootstrap());
    }

    public function testSingleModuleEnable()
    {
        Drunit::enableModule(__DIR__.'/../../modules/drunit_foo', array('drunit_test'));
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

    public function testSingleModuleBaseEnable()
    {
        Drunit::enableModule(__DIR__.'/../../modules/drunit_test4');
        $this->assertTrue(function_exists('drunit_test4'));
    }

    public function testHooks()
    {
        Drunit::enableModule(__DIR__.'/../../modules/drunit_test5', array('drunit_test5'));
        $this->assertTrue(in_array('init',drunit_test5_static()));
        $this->assertTrue(in_array('boot',drunit_test5_static()));
    }

      /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidModuleException()
    {
        Drunit::enableModule(__DIR__.'/../../modules/drunit_test6');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidLocationException()
    {
        Drunit::enableModule(__DIR__.'/../../modules/foobar');
    }
}
