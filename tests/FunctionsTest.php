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
			'Invalid Entry Type: Entry must be an array, or an object, string given.');
        Cartographer\get_property('foo', 'foo');
    }
}
