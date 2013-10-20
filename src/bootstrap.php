<?php

/**
 * This file is part of Drunit
 *
 * (c) Korstiaan de Ridder <korstiaan@korstiaan.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

if (defined('DRUPAL_PATH')) {
    define('DRUPAL_ROOT', realpath(DRUPAL_PATH));
} else {
    foreach (array(
        realpath(__DIR__.'/../vendor/drupal/core'),
        realpath(__DIR__.'/../../core'),
    ) as $path) {
        if (is_dir($path)) {
            define('DRUPAL_ROOT', $path);
            continue;
        }
    }
}

if (!defined('DRUPAL_ROOT') || !is_dir(DRUPAL_ROOT)) {
    throw new \RuntimeException('Drupal not found, make sure you have installed dependencies');
}

$db   = sys_get_temp_dir().'/drupal_'.uniqid(md5(DRUPAL_ROOT),true).'.db';

$copy = @copy(__DIR__.'/../baseline/drupal.db',$db);

if (false === $copy || !file_exists($db)) {
    throw new \RuntimeException(sprintf('Unable to copy Drupal baseline to %s',$db));
}

$GLOBALS['databases'] = array(
    'default' => array(
        'default' => array(
            'driver' 	=> 'sqlite',
            'database' 	=> $db,
        ),
    ),
);

chdir(DRUPAL_ROOT);

$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

include 'includes/bootstrap.inc';

drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
