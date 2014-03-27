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

use Drunit\TestCase;

use Drunit\Installer;

use Drunit\Drunit;

class InstallerTest extends \PHPUnit_Framework_TestCase
{
    protected $locater;
    protected $installer;
    protected $drupal;
    public function setUp()
    {
        $this->locater = new PackageLocater(__DIR__);
        $this->installer = new Installer($this->locater->locate('drush/drush').'/drush.php');
        $this->drupal = $this->locater->locate('drupal/core');
    }

    public function testReinstall()
    {
        $db = sys_get_temp_dir().'/foo.'.uniqid(null,true).'.db';
        $install = $this->installer->reinstall($this->drupal, "sqlite:{$db}");
        $this->assertNull($install);
        $this->assertTrue(file_exists($db));
    }

    public function testSettingsRemoved()
    {
        $db = sys_get_temp_dir().'/foo.'.uniqid(null,true).'.db';
        $install = $this->installer->reinstall($this->drupal, "sqlite:{$db}");
        $this->assertFalse(file_exists($this->drupal.'/sites/default/settings.php'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNoDrush()
    {
        $installer = new Installer('/foo/bar');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNoDrupal()
    {
        $install = $this->installer->reinstall('/foo/bar', null);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testInvalidInstall()
    {
        $install = $this->installer->reinstall(sys_get_temp_dir(), "sqlite:foo");
    }
}
