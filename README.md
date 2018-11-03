# php-simpcli
A simple PHP cli arguments parser

## Installation

    composer require pangodream/php-simpcli

## Usage:

```php
require_once __DIR__.'/../vendor/autoload.php';

use PhpSimpcli\CliParser;

$sp = new CliParser();

var_dump($sp->get('myOption'));
```
From the cli console:

    php test.php -otherOption 

    ["found"] => bool(false)
    ["value"] => NULL
    ["type"]  => NULL
    
    php test.php -myOption 

    ["found"] => bool(true)
    ["value"] => NULL
    ["type"]  => "missing"

    php test.php -myOption Hello

    ["found"] => bool(true)
    ["value"] => "Hello"
    ["type"]  => "single"

    php test.php -myOption Hello World

    ["found"] => bool(true)
    ["value"] => array([0] => "Hello, [1] => "World")
    ["type"]  => multi
    
    php test.php -myOption Hello "Wonderful World"

    ["found"] => bool(true)
    ["value"] => array([0] => "Hello, [1] => "Wonderful World")
    ["type"]  => multi

    

    
