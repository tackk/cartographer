<?php

class SitemapNewsTest extends PHPUnit_Framework_TestCase
{

    public function testToString()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
  <url>
    <loc>http://foo.com/1.html</loc>
    <news:news>
      <news:publication>
        <news:name>Name1</news:name>
        <news:language>en</news:language>
      </news:publication>
      <news:access>Subscription</news:access>
      <news:genres>PressRelease, Blog</news:genres>
      <news:publication_date>2016-09-06</news:publication_date>
      <news:title>Title1</news:title>
      <news:keywords>keyword11, keyword12</news:keywords>
      <news:stock_tickers>NASDAQ:A, NASDAQ:B</news:stock_tickers>
    </news:news>
  </url>
  <url>
    <loc>http://foo.com/2.html</loc>
    <news:news>
      <news:publication>
        <news:name>Name2</news:name>
        <news:language>es</news:language>
      </news:publication>
      <news:access>Registration</news:access>
      <news:genres>Satire, Opinion</news:genres>
      <news:publication_date>2016-09-07</news:publication_date>
      <news:title>Title2</news:title>
      <news:keywords>keyword21, keyword22</news:keywords>
    </news:news>
  </url>
</urlset>

XML;
        $sitemapNews = new Tackk\Cartographer\SitemapNews();
        $news1 = new Tackk\Cartographer\News();
        $news1->setName('Name1')
            ->setLanguage('en')
            ->setAccess('Subscription')
            ->setGenres('PressRelease, Blog')
            ->setPublicationDate('2016-09-06')
            ->setTitle('Title1')
            ->setKeywords('keyword11, keyword12')
            ->setStockTickers('NASDAQ:A, NASDAQ:B');

        $news2 = new Tackk\Cartographer\News();
        $news2->setName('Name2')
            ->setLanguage('es')
            ->setAccess('Registration')
            ->setGenres('Satire, Opinion')
            ->setPublicationDate('2016-09-07')
            ->setTitle('Title2')
            ->setKeywords('keyword21, keyword22');


        $sitemapNews->add('http://foo.com/1.html', $news1);
        $sitemapNews->add('http://foo.com/2.html', $news2);
        $this->assertEquals($expected, $sitemapNews->toString());
    }
}