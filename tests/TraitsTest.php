<?php

namespace Tests;

use Jasonej\BootableTraits\Traits;
use PHPUnit\Framework\TestCase;

final class TraitsTest extends TestCase
{
    /** @test */
    public function it_detects_traits_used_directly_on_class()
    {
        $fooTraits = Traits::usedByClass(Foo::class);
        $barTraits = Traits::usedByClass(Bar::class);
        $bazTraits = Traits::usedByClass(Baz::class);

        $this->assertArrayHasKey(Example::class, $fooTraits);
        $this->assertArrayHasKey(Example2::class, $barTraits);
        $this->assertArrayHasKey(Example3::class, $bazTraits);
        $this->assertCount(1, $fooTraits);
        $this->assertCount(1, $barTraits);
        $this->assertCount(1, $bazTraits);
    }

    /** @test */
    public function it_detects_traits_on_parent_class_in_recursive_mode()
    {
        $traits = Traits::usedByClass(Bar::class, true);

        $this->assertArrayHasKey(Example::class, $traits);
        $this->assertArrayHasKey(Example2::class, $traits);
        $this->assertCount(2, $traits);
    }

    /** @test */
    public function it_detects_nested_and_grandparent_traits_in_recursive_mode()
    {
        $traits = Traits::usedByClass(Baz::class, true);

        $this->assertArrayHasKey(Example::class, $traits);
        $this->assertArrayHasKey(Example2::class, $traits);
        $this->assertArrayHasKey(Example3::class, $traits);
        $this->assertArrayHasKey(Example4::class, $traits);
        $this->assertCount(4, $traits);
    }

    /** @test */
    public function it_detects_bootable_traits()
    {
        $bootableTraits = Traits::traitBootMethods(Baz::class);

        $this->assertArrayHasKey(Example3::class, $bootableTraits);
        $this->assertArrayHasKey(Example4::class, $bootableTraits);
        $this->assertCount(2, $bootableTraits);
    }
}

trait Example {}
trait Example2 {}
trait Example3 {
    use Example4;

    public static function bootExample3(): void
    {

    }
}
trait Example4 {
    public function bootExample4(): void
    {

    }
}

class Foo
{
    use Example;
}

class Bar extends Foo
{
    use Example2;
}

class Baz extends Bar
{
    use Example3;
}