<?php

namespace Tackk\Cartographer;

use ArrayObject;
use DateTime;
use Iterator;
use League\Flysystem\FilesystemInterface;
use RuntimeException;

class GoogleSitemapFactory extends SitemapFactory
{

    /**
     * @return GoogleSitemap
     */
    protected function instantiateNewSitemap()
    {
        return new GoogleSitemap();
    }

    protected function addEntry($entry, AbstractSitemap $sitemap)
    {
        if (!$sitemap instanceof GoogleSitemap) {
            throw new \Exception('Bad sitemap type. Must be a google site map instance');
        }

        parent::addEntry($entry, $sitemap);

        $images = get_property($entry, 'images');

        if ($images) {
            foreach ($images as $image) {

                $loc        = get_property($image, 'loc');
                $title      = get_property($image, 'title');
                $caption    = get_property($image, 'caption');
                $geo_location = get_property($image, 'geo_location');
                $license    = get_property($image, 'license');

                $sitemap->addImage($loc, $title, $caption, $geo_location, $license);
            }
        }


    }



}
