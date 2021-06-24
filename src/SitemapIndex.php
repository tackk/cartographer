<?php

namespace Tackk\Cartographer;

class SitemapIndex extends AbstractSitemap
{
    protected function getRootNodeName(): string
    {
        return 'sitemapindex';
    }

    protected function getNodeName(): string
    {
        return 'sitemap';
    }

    /**
     * Adds the URL to the sitemapindex.
     */
    public function add(string $loc, string $lastmod = null): self
    {
        $loc     = $this->escapeString($loc);
        $lastmod = !is_null($lastmod) ? $this->formatDate($lastmod) : null;

        return $this->addUrlToDocument(compact('loc', 'lastmod'));
    }
}
