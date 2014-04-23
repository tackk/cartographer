<?php
use Tackk\Cartographer\Sitemap;
use Tackk\Cartographer\ChangeFrequency;

class SitemapTest extends PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>http://foo.com</loc>
    <lastmod>2005-01-02T00:00:00+00:00</lastmod>
    <changefreq>weekly</changefreq>
    <priority>1</priority>
  </url>
  <url>
    <loc>http://foo.com/about</loc>
    <lastmod>2005-01-01T00:00:00+00:00</lastmod>
  </url>
</urlset>

XML;
        $sitemap = new Sitemap();
        $sitemap->add('http://foo.com', '2005-01-02', ChangeFrequency::WEEKLY, 1.0);
        $sitemap->add('http://foo.com/about', '2005-01-01');
        $this->assertEquals($expected, $sitemap->toString());
    }
}
