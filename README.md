# Drunit for Drupal 7.x

Drupal bootstrapper to ease integration/functional testing your Drupal modules. 

[![Build Status](https://secure.travis-ci.org/korstiaan/drunit.png)](http://travis-ci.org/korstiaan/drunit)

## Requirements

* PHP 5.3.3+ (unfortunately the SQLite implementation in Drupal is not compatible with PHP 5.4)
* PDO SQLite driver
* Any other extension Drupal 7.* requires

## Installation

The recommended way to install `Drunit` is with [composer](http://getcomposer.org). 
Just add the following to your `composer.json`:

```json
   {
       "require-dev": {
       	   ...
           "korstiaan/drunit": "*"
       }
   }
```

Now update composer and install the newly added requirement and its dependencies:

``` bash
$ php composer.phar update korstiaan/drunit --dev
```

## Usage

### Bootstrap Drupal

First initialize composer's autoloading by including it in your tests bootstrap file:

```php
// tests/bootstrap.php
use Drunit\Drunit;

require __DIR__ . '/../vendor/autoload.php';
 
```

### Bootstrap Drupal on each test

`Drunit` provides a TestCase class which bootstraps Drupal to its final phase `DRUPAL_BOOTSTRAP_FULL` 
and makes sure each test is run in its own isolated process. Just extend it to make use of it:

```php
use Drunit\TestCase;

class FooTest extends TestCase 
{
    ...
}
```

### Enable your module(s)

Next enable a module by adding the following: 

```php
// tests/bootstrap.php

Drunit::enableModule(__DIR__.'/../module', array('my_module'));
```

This will enable module `my_module` located at `__ROOT__.'/module'` (Drupal recursively looks for file `my_module.module`).

If you have multiple modules located in a single directory (for example `__ROOT__.'/modules/my_module1'` and `__ROOT__.'/modules/my_module2`),
you can enable them all as follows:

```php
Drunit::enableModule(__DIR__.'/../modules', array('my_module1', 'my_module2'));
```

If your module name is the same as the base name of the directory you can leave out the 2nd parameter:

```php
Drunit::enableModule(__DIR__.'/../modules/my_module1');
```

# License

Drunit is licensed under the MIT license.
