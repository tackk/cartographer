<?php

namespace Tackk\Cartographer;

class SitemapIndex extends AbstractSitemap
{
    protected function getRootNodeName()
    {
        return 'sitemapindex';
    }

    protected function getNodeName()
    {
        return 'sitemap';
    }

    /**
     * Adds the URL to the sitemapindex.
     * @param  string     $loc
     * @param  string|int $lastmod
     * @return $this
     */
    public function add($loc, $lastmod)
    {
        $loc     = $this->escapeString($loc);
        $lastmod = !is_null($lastmod) ? $this->formatDate($lastmod) : null;

        return $this->addUrlToDocument(compact('loc', 'lastmod'));
    }
}
