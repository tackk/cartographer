<?php

class SitemapIndexTest extends PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap>
    <loc>http://foo.com/sitemaps/sitemap.1.xml</loc>
    <lastmod>2012-01-02T00:00:00+00:00</lastmod>
  </sitemap>
  <sitemap>
    <loc>http://foo.com/sitemaps/sitemap.2.xml</loc>
    <lastmod>2012-01-02T00:00:00+00:00</lastmod>
  </sitemap>
</sitemapindex>

XML;
        $sitemapIndex = new Tackk\Cartographer\SitemapIndex();
        $sitemapIndex->add('http://foo.com/sitemaps/sitemap.1.xml', '2012-01-02');
        $sitemapIndex->add('http://foo.com/sitemaps/sitemap.2.xml', '2012-01-02');
        $this->assertEquals($expected, $sitemapIndex->toString());
    }
}
