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
        static $done = null; if (true === $done) return; $done = true;
         
        require_once __DIR__.'/../bootstrap.php';
    }
    
    /**
     * 
     * Enter description here ...
     * @param unknown_type $loc
     * @param unknown_type $name
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
        if (!is_dir($link)) {
            symlink($loc, $link);
        }
        module_enable((array) ($name ?: $base));
    }
}
