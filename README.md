# Cartographer

[![Latest Stable Version](https://poser.pugx.org/tackk/cartographer/version.png)](https://packagist.org/packages/tackk/cartographer)
[![Total Downloads](https://poser.pugx.org/tackk/cartographer/d/total.png)](https://packagist.org/packages/tackk/cartographer)
[![License](https://poser.pugx.org/tackk/cartographer/license.png)](https://packagist.org/packages/tackk/cartographer)

[![Build Status](https://travis-ci.org/tackk/cartographer.svg)](https://travis-ci.org/tackk/cartographer)
[![Code Coverage](https://scrutinizer-ci.com/g/tackk/cartographer/badges/coverage.png?s=5547a47fb7e014a26cc4b43f69832f82b673d8ba)](https://scrutinizer-ci.com/g/tackk/cartographer/)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tackk/cartographer/badges/quality-score.png?s=47b9d98507fa3ea5be94ef3656a3de5a5bff662d)](https://scrutinizer-ci.com/g/tackk/cartographer/)

A sitemap generation tool for PHP following the [Sitemap Protocol v0.9](http://www.sitemaps.org/protocol.html).

Cartographer can handle Sitemaps of any size.  When generating sitemaps with more than 50,000
entries (the limit), the sitemap becomes a "map of maps" (i.e. nested sitemaps).

* **GitHub Repo:** [http://github.com/tackk/cartographer/](http://github.com/tackk/cartographer/)
* **Documentation:** [http://tackk.github.io/cartographer/](http://tackk.github.io/cartographer/)

## Supported PHP/HHVM Versions

* **PHP:** >= 5.4 (including 5.6 beta1)
* **HHVM:** >= 3.0.0

## Installation

### Composer CLI

```
composer require tackk/cartographer:1.0.*
```

### composer.json

``` json
{
    "require": {
        "tackk/cartographer": "1.0.*"
    }
}
```

## Basic Sitemap

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

### Output

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

## Basic Sitemap Index

If you want to build a Sitemap Index, separate from the Sitemap Generator, you can!

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

### Output

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

## Sitemap Factory

The Sitemap Factory create Sitemaps and Sitemap Indexes and writes them to the Filesystem.
Is is can be used to generate full Sitemaps with more than **50,000** URLs.

If more than one sitemap is generated, it will create a Sitemap Index automatically.

### Instantiating

The factory uses [Flysystem](http://flysystem.thephpleague.com/) to write the sitemaps.  This
means you can write the sitemaps to Local Disk, S3, Dropbox, wherever you want.

``` php
<?php

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as LocalAdapter;

$adapter = new LocalAdapter(__DIR__.'/sitemaps');
$filesystem = new Filesystem($adapter);
$sitemapFactory = new Tackk\Cartographer\SitemapFactory($filesystem);

```

### Base URL

The Base URL is used when generating the Sitemap Indexes, and for the returned entry point URL.

You can set the Base URL:

``` php
$sitemapFactory->setBaseUrl('http://foo.com/sitemaps/');
```

You can get the current base URL using `getBaseUrl()`.

### Creating a Sitemap

To create a sitemap you use the `createSitemap` method.  This method requires an `Iterator` as
its only parameter.

``` php
<?php

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as LocalAdapter;

$adapter = new LocalAdapter(__DIR__.'/sitemaps');
$filesystem = new Filesystem($adapter);
$sitemapFactory = new Tackk\Cartographer\SitemapFactory($filesystem);

// Create an Iterator of your URLs somehow.
$urls = get_url_iterator();

// Returns the URL to the main Sitemap/Index file
$mainSitemap = $sitemapFactory->createSitemap($urls);

```

### Return Value

The two creation methods (`createSitemap` and `createSitemapIndex`) will return the URL
of the root sitemap file.  If there is only 1 sitemap created, it will return just that URL.
If multiple sitemaps are created, then a Sitemap Index is generated and the URL to that is returned.

### List of Created Files

You can get a list (array) of files the Factory has created by using the `getFilesCreated` method.

``` php
$files = $sitemapFactory->getFilesCreated();
```

## Running Tests

*This assumes you have ran `composer update`.*

From the repository root, run:

```
vendor/bin/phpunit
```
