<?php
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as LocalAdapter;

class GeneratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Tackk\Cartographer\SitemapFactory
     */
    protected $factory;

    /**
     * @var League\Flysystem\Filesystem
     */
    protected $filesystem;

    public function setUp()
    {
        $adapter = new LocalAdapter(__DIR__.'/storage/sitemaps');
        $this->filesystem = new Filesystem($adapter);
        $this->factory = new Tackk\Cartographer\SitemapFactory($this->filesystem);
    }

    public function testCanInstantiate()
    {
        $this->assertInstanceOf('Tackk\Cartographer\SitemapFactory', $this->factory);
    }

    public function testGetFilesystem()
    {
        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $this->factory->getFilesystem());
    }

    public function testSetBaseUrl()
    {
        $this->factory->setBaseUrl('foo/');
        $this->assertAttributeEquals('foo', 'baseUrl', $this->factory);

        $this->factory->setBaseUrl('foo');
        $this->assertAttributeEquals('foo', 'baseUrl', $this->factory);
    }

    public function testGetBaseUrl()
    {
        $class = new ReflectionClass($this->factory);
        $baseUrl = $class->getProperty('baseUrl');
        $baseUrl->setAccessible(true);
        $baseUrl->setValue($this->factory, 'foo');

        $this->assertEquals('foo', $this->factory->getBaseUrl());
    }

    public function testGetFileCreated()
    {
        $class = new ReflectionClass($this->factory);
        $filesCreated = $class->getProperty('filesCreated');
        $filesCreated->setAccessible(true);
        $filesCreated->setValue($this->factory, ['foo.txt']);

        $this->assertEquals(['foo.txt'], $this->factory->getFilesCreated());
    }

    public function testCreateRequiresIterator()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');
        $this->factory->createSitemap();
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
        $urls = [];
        for ($i = 1; $i <= 3; $i++) {
            $urls[] = ['url' => 'http://foo.com/'.$i];
        }
        $path = $this->factory->createSitemap(new ArrayIterator($urls));

        $actual = $this->filesystem->read($path);
        $this->filesystem->delete($path);
        $this->assertEquals($expected, $actual);
    }

    public function testLargeSitemapCreatesIndex()
    {
        $urls = [];
        for ($i = 1; $i <= 50002; $i++) {
            $urls[] = ['url' => 'http://foo.com/'.$i];
        }
        $path = $this->factory->createSitemap(new ArrayIterator($urls));
        $this->assertTrue($this->filesystem->has($path));

        foreach ($this->factory->getFilesCreated() as $file) {
            $this->assertTrue($this->filesystem->has($file));
            $this->filesystem->delete($file);
        }
    }

    public function testUrlMustBePresent()
    {
        $this->setExpectedException('InvalidArgumentException', 'Url is missing or not accessible.');
        $this->factory->createSitemap(new ArrayIterator([[]]));
    }
}
