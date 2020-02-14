# Bootable Traits

A simple package for enabling the booting of traits.

## Installation
```
composer require jasonej/bootable-traits
```

## Usage
```php
<?php

use Jasonej\BootableTraits\BootsTraits;

trait ExampleTrait
{
    public $booted = false;

    public function bootExampleTrait(): void
    {
        $this->booted = true;
    }
}

class ExampleClass
{
    use BootsTraits;
    use ExampleTrait;

    public function __construct()
    {
        static::bootTraits($this);
    }
}
```

```php
<?php

use Jasonej\BootableTraits\BootsTraits;

trait ExampleTrait
{
    public static $booted = false;

    public static function bootExampleTrait(): void
    {
        static::$booted = true;
    }
}

class ExampleClass
{
    use BootsTraits;
    use ExampleTrait;

    public static function init()
    {
        static::bootTraits();
    }
}
```