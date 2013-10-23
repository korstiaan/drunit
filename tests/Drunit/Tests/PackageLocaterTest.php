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


use Drunit\Composer\PackageLocater;

class PackageLocaterTest extends \PHPUnit_Framework_TestCase
{
    protected $fixturedir;
    public function setUp()
    {
        $this->fixturedir = realpath(__DIR__.'/../../packagelocater');
    }
    
    public function testCanLocateMyVendors()
    {
        $locater = new PackageLocater(__DIR__);
        $this->assertEquals(realpath(__DIR__.'/../../../vendor/drush/drush'), $locater->locate('drush/drush'));
    }
    
    public function testOutsideVendor()
    {
        $locater = new PackageLocater($this->fixturedir.'/fs/bar/crux');
        $this->assertEquals($this->fixturedir.'/fs/vendor/foo/bar', $locater->locate('foo/bar'));
    }
    
    public function testWithinVendor()
    {
        $locater = new PackageLocater($this->fixturedir.'/fs/vendor/bar/crux');
        $this->assertEquals($this->fixturedir.'/fs/vendor/foo/bar', $locater->locate('foo/bar'));
    }
    
    public function testVendorDirCached()
    {
        $locater = new PackageLocater($this->fixturedir.'/fs/vendor/bar/crux');
        $this->assertEquals($locater->locate('foo/bar'), $locater->locate('foo/bar'));
    }
    
    /**
     * @expectedException RuntimeException
     */
    public function testCantLocate()
    {
        $locater = new PackageLocater($this->fixturedir.'/fs');
        $locater->locate('crux/bar');
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidRoot()
    {
        new PackageLocater(__DIR__.'/bla/bla');
    }
    
    /**
     * @expectedException RuntimeException
     */
    public function testNoComposerDir()
    {
        $locator = new PackageLocater(sys_get_temp_dir());
        $locator->locate('foo/bar');
    }
    
}
