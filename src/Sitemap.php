<?php

namespace Tackk\Cartographer;

class Sitemap extends AbstractSitemap
{
    protected function getRootNodeName(): string
    {
        return 'urlset';
    }

    protected function getNodeName(): string
    {
        return 'url';
    }

    /**
     * Adds the URL to the urlset.
     */
    public function add(string $loc, string $lastmod = null, string $changefreq = null, float $priority = null): self
    {
        $loc     = $this->escapeString($loc);
        $lastmod = !is_null($lastmod) ? $this->formatDate($lastmod) : null;

        return $this->addUrlToDocument(compact('loc', 'lastmod', 'changefreq', 'priority'));
    }
}
