<?php

namespace Tackk\Cartographer;

class Sitemap extends AbstractSitemap
{
    protected function getRootNodeName()
    {
        return 'urlset';
    }

    protected function getNodeName()
    {
        return 'url';
    }

    /**
     * Adds the URL to the urlset.
     * @param  string     $loc
     * @param  string|int $lastmod
     * @param  string     $changefreq
     * @param  float      $priority
     * @return $this
     */
    public function add($loc, $lastmod = null, $changefreq = null, $priority = null)
    {
        $loc     = $this->escapeString($loc);
        $lastmod = !is_null($lastmod) ? $this->formatDate($lastmod) : null;

        return $this->addUrlToDocument(compact('loc', 'lastmod', 'changefreq', 'priority'));
    }
}
