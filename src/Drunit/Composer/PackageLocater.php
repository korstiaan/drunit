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

class PackageLocater
{
    private $root;
    private $vendor;
    
    public function __construct($root) 
    {
        if (!is_dir($root)) {
            throw new \InvalidArgumentException(sprintf('%s isn\'t a valid directory', $root));
        }
        
        $this->root = realpath($root);
    }
    
    public function locate($package)
    {
        $root = $this->getVendorRoot();
        $dir  = "{$root}/{$package}";
        
        if (is_dir($dir)) {
            return $dir;
        }
        
        throw new \RuntimeException(sprintf('Unable to locate package in %s', $root));
    }
    
    private function getVendorRoot() 
    {
        if (null !== $this->vendor) {
            return $this->vendor;
        }
        
        $dir    = $this->root;
        
        while($dir && $dir !== DIRECTORY_SEPARATOR) {
            
            if (true === $this->isVendorDir($dir)) {
                return $this->vendor = $dir; 
            }
            
            $vendorDir = "{$dir}/vendor";
            
            if (is_dir($vendorDir) && true === $this->isVendorDir($vendorDir)) {
                return $this->vendor = $vendorDir;
            }
            
            $dir = dirname($dir); 
        }
        
        throw new \RuntimeException('Unable to locate vendor dir');
    }
    
    private function isVendorDir($dir)
    {
        return file_exists("{$dir}/autoload.php") && is_dir("{$dir}/composer");
    }
}
