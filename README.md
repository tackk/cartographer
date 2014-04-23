# Cartographer

A sitemap generation tool for PHP following the [Sitemap Protocol v0.9](http://www.sitemaps.org/protocol.html).

Cartographer can handle Sitemaps of any size.  When generating sitemaps with more than 50,000
entries (the limit), the sitemap becomes a "map of maps" (i.e. nested sitemaps).

## Usage

**Note: This is still a work in progress, so this may change, and it most certainly is not yet stable for
production.**

### Basic Sitemap

If you have a sitemap that is under 50,000 items, you can just use the Sitemap class, and avoid the Sitemap
Generator.

``` php
use Tackk\Cartographer\Sitemap;
use Tackk\Cartographer\ChangeFrequency;

$sitemap = new Tackk\Cartographer\Sitemap();
$sitemap->add('http://foo.com', '2005-01-02', ChangeFrequency::WEEKLY, 1.0);
$sitemap->add('http://foo.com/about', '2005-01-01');

// Write it to a file
file_put_contents('sitemap.xml', (string) $sitemap);

// or simply echo it:
header ('Content-Type:text/xml');
echo $sitemap->toString();
```

#### Output

``` xml
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
```

### Basic Sitemap Index

If you want to build a Sitemap Index, seperate from the Sitemap Generator, you can!

``` php
$sitemapIndex = new Tackk\Cartographer\SitemapIndex();
$sitemapIndex->add('http://foo.com/sitemaps/sitemap.1.xml', '2012-01-02');
$sitemapIndex->add('http://foo.com/sitemaps/sitemap.2.xml', '2012-01-02');

// Write it to a file
file_put_contents('sitemap.xml', (string) $sitemapIndex);

// or simply echo it:
header ('Content-Type:text/xml');
echo $sitemapIndex->toString();
```

#### Output

``` xml
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>http://foo.com/sitemaps/sitemap.1.xml</loc>
    <lastmod>2012-01-02T00:00:00+00:00</lastmod>
  </url>
  <url>
    <loc>http://foo.com/sitemaps/sitemap.2.xml</loc>
    <lastmod>2012-01-02T00:00:00+00:00</lastmod>
  </url>
</sitemapindex>
```

### Sitemap Generator

**Still under heavy development.  DO NOT USE.**

``` php
<?php
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as LocalAdapter;

$adapter = new LocalAdapter(__DIR__);
$filesystem = new Filesystem($adapter);
$generator = new Tackk\Cartographer\Generator($filesystem);
```

## Running Tests

*This assumes you have ran `composer update`.*

From the repository root, run:

```
vendor/bin/phpunit
```
