<?php

namespace Tests;

use Jasonej\BootableTraits\BootsTraits;
use PHPUnit\Framework\TestCase;

final class BootsTraitsTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        StaticAncestor::$staticallyBooted = false;
        StaticDescendant::$staticallyBooted = false;
    }

    /** @test */
    public function it_boots_traits()
    {
        $object = new Ancestor;

        $this->assertTrue($object->booted);
        $this->assertTrue($object::$staticallyBooted);
    }

    /** @test */
    public function it_boots_ancestor_traits()
    {
        $object = new Descendant;

        $this->assertTrue($object->booted);
        $this->assertTrue($object::$staticallyBooted);
    }

    /** @test */
    public function it_boots_static_traits()
    {
        StaticAncestor::init();

        $this->assertTrue(StaticAncestor::$staticallyBooted);
    }

    /** @test */
    public function it_boots_static_ancestor_traits()
    {
        StaticDescendant::init();

        $this->assertTrue(StaticDescendant::$staticallyBooted);
    }
}

trait BootableExample
{
    public $booted = false;

    public function bootBootableExample(): void
    {
        $this->booted = true;
    }
}

trait StaticExample
{
    public static $staticallyBooted = false;

    public static function bootStaticExample(): void
    {
        static::$staticallyBooted = true;
    }
}

class Ancestor
{
    use BootsTraits;
    use BootableExample;
    use StaticExample;

    public function __construct()
    {
        static::bootTraits($this);
    }
}

class Descendant extends Ancestor {}

class StaticAncestor
{
    use BootsTraits;
    use StaticExample;

    public static function init()
    {
        static::bootTraits();
    }
}

class StaticDescendant extends StaticAncestor {}