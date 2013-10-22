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

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        $this->setRunTestInSeparateProcess(true);
        parent::__construct($name, $data, $dataName);
    }
    
    public function setUp()
    {
        Drunit::bootstrap();
    }
}
