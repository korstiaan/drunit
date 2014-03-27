<?php

/**
 * This file is part of Drunit
 *
 * (c) Korstiaan de Ridder <korstiaan@korstiaan.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Drunit\Composer;

use Drunit\Installer;

use Symfony\Component\Process\Process;
use Composer\Script\Event;
use Composer\Composer;

class ScriptHandler
{
    public static function installDrupal(Event $event)
    {
        $event->getIO()->write('<info>Installing drupal</info>');
        $composer = $event->getComposer();

        $drush    = self::getPackageLocation($composer, 'drush/drush').'/drush.php';

        $drupal   = self::getPackageLocation($composer, 'drupal/core');

        $root     = realpath(__DIR__.'/../../..');

        $db       = "sqlite:{$root}/db/drupal.db";

        $installer = new Installer($drush);
        $installer->reinstall($drupal, $db);
    }

    protected static function getPackageLocation(Composer $composer, $name)
    {
        $packages = $composer->getRepositoryManager()->getLocalRepository()->findPackages($name);

        if (empty($packages)) {
            throw new \RuntimeException(sprintf('Unable to find package %s', $name));
        }

        return $composer->getInstallationManager()->getInstallPath(reset($packages));
    }
}
