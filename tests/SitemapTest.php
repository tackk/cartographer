<?php

class SitemapTest extends PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>http://foo.com</loc>
    <lastmod>2005-01-02</lastmod>
    <changefreq>weekly</changefreq>
    <priority>1</priority>
  </url>
  <url>
    <loc>http://foo.com/about</loc>
    <lastmod>2005-01-01</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
</urlset>

XML;
        $sitemap = new Tackk\Cartographer\Sitemap();
        $sitemap->addUrl('http://foo.com', '2005-01-02', 'weekly', 1.0);
        $sitemap->addUrl('http://foo.com/about', '2005-01-01', 'weekly', 0.8);
        $this->assertEquals($expected, $sitemap->toString());
    }
}
