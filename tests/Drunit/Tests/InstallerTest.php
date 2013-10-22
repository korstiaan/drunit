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

use Drunit\TestCase;

use Drunit\Installer;

use Drunit\Drunit;

class InstallerTest extends TestCase
{
    public function setUp()
    {
        Drunit::bootstrap();
    }
    
    public function testReinstall()
    { 
        $installer = new Installer(__DIR__.'/../../../vendor/bin/drush');
        $db = sys_get_temp_dir().'/foo.'.uniqid(null,true).'.db';
        $install = $installer->reinstall(DRUPAL_ROOT, "sqlite:{$db}");
        $this->assertNull($install);
        $this->assertTrue(file_exists($db));
    }
    
    public function testSettingsRemoved()
    {
        $installer = new Installer(__DIR__.'/../../../vendor/bin/drush');
        $db = sys_get_temp_dir().'/foo.'.uniqid(null,true).'.db';
        $install = $installer->reinstall(DRUPAL_ROOT, "sqlite:{$db}");
        $this->assertFalse(file_exists(DRUPAL_ROOT.'/sites/default/settings.php'));
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
        $installer = new Installer(__DIR__.'/../../../vendor/bin/drush');
        $install = $installer->reinstall('/foo/bar', null);
    }
    
    /**
     * @expectedException RuntimeException
     */
    public function testInvalidInstall()
    {
        $installer = new Installer(__DIR__.'/../../../vendor/bin/drush');
        $install = $installer->reinstall(sys_get_temp_dir(), "sqlite:foo");
    }
}
