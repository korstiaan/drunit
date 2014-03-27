<?php

/**
 * This file is part of Drunit
 *
 * (c) Korstiaan de Ridder <korstiaan@korstiaan.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Drunit;

use Symfony\Component\Process\ProcessBuilder;

use Symfony\Component\Process\Process;

class Installer
{
    public function __construct($drush)
    {
        $this->drush = $drush;
        if (!is_executable($drush)) {
            throw new \InvalidArgumentException('Unable to find drush');
        }
    }

    public function reinstall($drupal, $dsn)
    {
        if (!is_dir($drupal)) {
            throw new \InvalidArgumentException('Unable to find Drupal dir');
        }

        $this->resetSettings($drupal);

        $process = $this->getProcess($drupal, $dsn);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }
        $this->resetSettings($drupal);
    }

    protected function resetSettings($drupal)
    {
        $settingsDir  = "{$drupal}/sites/default";
        $settingsFile = "{$settingsDir}/settings.php";
        if (is_dir($settingsDir)) {
            // Drush messes with file permissions, so correct those first
            chmod($settingsDir, 0777);
            if (file_exists($settingsFile)) {
                chmod($settingsFile, 0777);
                unlink($settingsFile);
            }
        }
    }

    protected function getProcess($drupal, $dsn)
    {
        $builder = new ProcessBuilder();
        $builder->inheritEnvironmentVariables(true);
        $builder->setWorkingDirectory($drupal);

        $builder->setPrefix('php');
        $builder->setArguments(array('-d sendmail_path=`which true`', $this->drush,'site-install', 'standard', "--db-url={$dsn}", '-y'));
        $process = $builder->getProcess();
        return $process;
    }
}

