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

    public function testSetIteratorThroughSetter()
    {
        $this->generator->setIterator(new ArrayIterator());
        $this->assertAttributeInstanceOf('Iterator', 'iterator', $this->generator);

        $this->setExpectedException('PHPUnit_Framework_Error');
        $this->generator->setIterator('foo');
    }

    public function testGenerateRequiresIterator()
    {
        $this->setExpectedException('RuntimeException');
        $this->generator->generate();
    }

    public function testCanGenerateSmallSitemap()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>http://foo.com/1</loc>
  </url>
  <url>
    <loc>http://foo.com/2</loc>
  </url>
  <url>
    <loc>http://foo.com/3</loc>
  </url>
</urlset>

XML;
        $this->generator->setIterator(new ArrayIterator([
            ['url' => 'http://foo.com/1'],
            ['url' => 'http://foo.com/2'],
            ['url' => 'http://foo.com/3'],
        ]));

        $actual = $this->generator->generate();
        $this->assertEquals($expected, $actual);
    }

    public function testUrlMustBePresent()
    {
        $this->generator->setIterator(new ArrayIterator([
            [],
        ]));

        $this->setExpectedException('InvalidArgumentException', 'Url is missing or not accessible.');
        $actual = $this->generator->generate();
    }
}
