<?php
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as LocalAdapter;

class GeneratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Tackk\Cartographer\Generator
     */
    protected $generator;

    public function setUp()
    {
        $adapter = new LocalAdapter(dirname(__DIR__).'/storage');
        $filesystem = new Filesystem($adapter);
        $this->generator = new Tackk\Cartographer\Generator($filesystem);
    }

    public function testCanInstantiate()
    {
        $this->assertInstanceOf('Tackk\Cartographer\Generator', $this->generator);
    }

    public function testGetFilesystem()
    {
        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $this->generator->getFilesystem());
    }
}
