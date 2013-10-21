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
        
        // Drush messes with file permissions, so correct those first
        $this->fixPermissions($drupal);
        
        $process = $this->getProcess($drupal, $dsn);

        $process->run();
        
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }
    }
    
    protected function fixPermissions($drupal) 
    {
        $settingsDir  = "{$drupal}/sites/default";
        $settingsFile = "{$settingsDir}/settings.php";
        if (!is_dir($settingsDir)) {
            return;
        }
        chmod($settingsDir, 0777);
        if (!file_exists($settingsFile)) {
            return;
        }
        chmod($settingsFile, 0777);
        unlink($settingsFile);
    }
    
    protected function getProcess($drupal, $dsn)
    {
        $builder = new ProcessBuilder();
        $builder->inheritEnvironmentVariables(true);
        $builder->setWorkingDirectory($drupal);
        
        $builder->setPrefix($this->drush);
        $builder->setArguments(array('site-install', 'standard', "--db-url={$dsn}", '-y'));
        $process = $builder->getProcess();
        return $process;
    }
}

