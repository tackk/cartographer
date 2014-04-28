<?php

use Tackk\Cartographer;


class FunctionsTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPropertyFromArray()
    {
        $entry = ['foo' => 'bar'];
        $this->assertEquals('bar', Cartographer\get_property($entry, 'foo'));
        $this->assertEquals(null, Cartographer\get_property($entry, 'foobar'));
        $this->assertEquals(false, Cartographer\get_property($entry, 'foobar', false));
    }

    public function testGetPropertyFromObject()
    {
        $entry = new stdClass();
        $entry->foo = 'bar';
        $this->assertEquals('bar', Cartographer\get_property($entry, 'foo'));
        $this->assertEquals(null, Cartographer\get_property($entry, 'foobar'));
        $this->assertEquals(false, Cartographer\get_property($entry, 'foobar', false));
    }

    public function testGetPropertyFromArrayAccessObject()
    {
        $entry = new ArrayIterator(['foo' => 'bar']);
        $this->assertEquals('bar', Cartographer\get_property($entry, 'foo'));
        $this->assertEquals(null, Cartographer\get_property($entry, 'foobar'));
        $this->assertEquals(false, Cartographer\get_property($entry, 'foobar', false));
    }

    public function testGetPropertyFromNonObject()
    {
        $this->setExpectedException('InvalidArgumentException',
            'Invalid type: string, Expected type(s): array, object, ArrayAccess');
        Cartographer\get_property('foo', 'foo');
    }

    public function testCheckTypePasses()
    {
        $this->assertTrue(Cartographer\checktype([], ['array']));
        $this->assertTrue(Cartographer\checktype([], ['ArrayAccess', 'array']));
    }

    public function testCheckTypeFails()
    {
        $this->setExpectedException('InvalidArgumentException',
            'Invalid type: ArrayObject, Expected type(s): array');
        Cartographer\checktype(new ArrayObject(), ['array']);
    }
}
