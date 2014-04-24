<?php
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as LocalAdapter;

class GeneratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Tackk\Cartographer\SitemapFactory
     */
    protected $factory;

    public function setUp()
    {
        $adapter = new LocalAdapter(dirname(__DIR__).'/storage');
        $filesystem = new Filesystem($adapter);
        $this->factory = new Tackk\Cartographer\SitemapFactory($filesystem);
    }

    public function testCanInstantiate()
    {
        $this->assertInstanceOf('Tackk\Cartographer\SitemapFactory', $this->factory);
    }

    public function testGetFilesystem()
    {
        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $this->factory->getFilesystem());
    }

    public function testCreateRequiresIterator()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');
        $this->factory->create();
    }

    public function testCanCreateSmallSitemap()
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
        $actual = $this->factory->create(new ArrayIterator([
            ['url' => 'http://foo.com/1'],
            ['url' => 'http://foo.com/2'],
            ['url' => 'http://foo.com/3'],
        ]));
        $this->assertEquals($expected, $actual);
    }

    public function testUrlMustBePresent()
    {
        $this->setExpectedException('InvalidArgumentException', 'Url is missing or not accessible.');
        $this->factory->create(new ArrayIterator([[]]));
    }
}
