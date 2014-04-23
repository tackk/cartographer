<?php

namespace Tackk\Cartographer;

class SitemapIndex extends AbstractSitemap
{
    protected function getRootNodeName()
    {
        return 'sitemapindex';
    }

    /**
     * Adds the URL to the sitemapindex.
     * @param  string     $loc
     * @param  string|int $lastmod
     * @return $this
     */
    public function add($loc, $lastmod)
    {
        $loc = $this->escapeString($loc);
        $lastmod = $this->formatDate($lastmod);
        return $this->addUrlToDocument(compact('loc', 'lastmod'));
    }
}
