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

abstract class Drunit
{
    /**
     * Convenience method to bootstrap Drupal, so you don't have to locate bootstrap.php yourself
     */
    public static function bootstrap()
    {
       require __DIR__.'/../bootstrap.php';
    }

    /**
     * Enables given module(s) at given location by symlinking their contents
     *
     * @param string            $loc  the location the module(s) can be found
     * @param array|string|null $name the name(s) of the module(s). Defaults to basename($loc).
     *
     * @throws \InvalidArgumentException
     */
    public static function enableModule($loc, $name = null)
    {
        $loc = rtrim($loc, '/');

        if (!is_dir($loc) || !is_readable($loc)) {
            throw new \InvalidArgumentException(sprintf('Unable to read directory %s', $loc));
        }

        $base = basename($loc);
        $link = DRUPAL_ROOT."/sites/all/modules/{$base}";
        
        if (file_exists($link) && readlink($link)) {
            unlink($link);    
        }
        
        symlink($loc, $link);
        
        drupal_static('system_rebuild_module_data', null, true);

        $modules = (array) ($name ?: $base);
        $enabled = module_enable($modules);

        if (false === $enabled) {
            throw new \InvalidArgumentException(sprintf('Unable to enable module(s) "%s"', implode(',', $modules)));
        }

        module_invoke_all('boot');
        module_invoke_all('init');
    }
}
