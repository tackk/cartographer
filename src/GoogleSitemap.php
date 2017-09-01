<?php
/**
 * @license see LICENSE
 */

namespace Tackk\Cartographer;


class GoogleSitemap extends Sitemap
{

    const IMAGE_EXTENSION_URI = 'http://www.google.com/schemas/sitemap-image/1.1';

    protected function getNamespaceExtensions()
    {
        $extensions = parent::getNamespaceExtensions();
        $extensions['xmlns:image'] = self::IMAGE_EXTENSION_URI;

        return $extensions;
    }

    /**
     * Adds the URL to the urlset.
     * @param  string $loc
     * @param  string|int $lastmod
     * @param  string $changefreq
     * @param  float $priority
     * @param  array|null $images
     * @return $this
     */
    public function add($loc, $lastmod = null, $changefreq = null, $priority = null, array $images = null)
    {
        parent::add($loc, $lastmod, $changefreq, $priority);

        if ($images) {
            foreach ($images as $imageData) {
                $this->addImage($imageData);
            }
        }

        return $this;
    }

    /**
     * @param $imageData
     * @return $this
     * @throws \Exception
     */
    public function addImage ($loc, $title = null, $caption = null, $geo_location = null, $license = null)
    {
        $this->hasImageExtension = true;
        $node = $this->getLastUrlNode();

        if (!$node) {
            throw new \Exception('Cannot add image when no url was already added');
        }

        $imageNode = $this->document->createElementNS(self::IMAGE_EXTENSION_URI, 'image');

        $imageData = compact('loc', 'title', 'caption', 'geo_location', 'license');

        foreach ($imageData as $key => $value) {
            if (is_null($value)) {
                continue;
            }

            $imageNode->appendChild($this->document->createElementNS(self::IMAGE_EXTENSION_URI, $key, $value));
        }

        $node->appendChild($imageNode);

        return $this;
    }
}