# Cartographer

A PHP sitemap generation tool.  This tool can handle Sitemaps of any size.  When generating sitemaps
with more than 50,000 entries (the limit), the sitemap becomes a "map of maps" (i.e. nested sitemaps).

## Usage

**Note: This is still a work in progress, so this may change, and it most certainly is not yet stable for
production.**

``` php
<?php
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as LocalAdapter;

$adapter = new LocalAdapter(__DIR__);
$filesystem = new Filesystem($adapter);
$sitemap = new Tackk\Cartographer\Sitemap($filesystem);
```

## Running Tests

*This assumes you have ran `composer update`.*

From the repository root, run:

```
vendor/bin/phpunit
```
