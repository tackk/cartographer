<?php
use Tackk\Cartographer\Sitemap;
use Tackk\Cartographer\ChangeFrequency;
use Tackk\Cartographer\GoogleSitemap;

class GoogleSitemapTest extends PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
  <url>
    <loc>http://foo.com</loc>
    <lastmod>2005-01-02T00:00:00+00:00</lastmod>
    <changefreq>weekly</changefreq>
    <priority>1</priority>
  </url>
  <url>
    <loc>http://foo.com/about</loc>
    <lastmod>2005-01-01T00:00:00+00:00</lastmod>
    <image:image>
      <image:loc>http://foo.com/foo.png</image:loc>
    </image:image>
    <image:image>
      <image:loc>http://foo.com/bar.png</image:loc>
    </image:image>
  </url>
  <url>
    <loc>http://foo.com/bar</loc>
    <lastmod>2005-01-01T00:00:00+00:00</lastmod>
    <image:image>
      <image:loc>http://foo.com/baz.jpg</image:loc>
      <image:title>some title</image:title>
      <image:caption>some caption</image:caption>
      <image:geo_location>somewhere</image:geo_location>
      <image:license>http://foo.com/license</image:license>
    </image:image>
  </url>
</urlset>

XML;
        $sitemap = new GoogleSitemap();
        $sitemap->add('http://foo.com', '2005-01-02', ChangeFrequency::WEEKLY, 1.0);
        $sitemap->add('http://foo.com/about', '2005-01-01');
        $sitemap->addImage('http://foo.com/foo.png');
        $sitemap->addImage('http://foo.com/bar.png');
        $sitemap->add('http://foo.com/bar', '2005-01-01');
        $sitemap->addImage('http://foo.com/baz.jpg', 'some title', 'some caption', 'somewhere', 'http://foo.com/license');
        $this->assertEquals($expected, $sitemap->toString());
    }
}
