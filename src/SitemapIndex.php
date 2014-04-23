<?php

namespace Tackk\Cartographer;

use DOMElement;

class SitemapIndex extends AbstractSitemap
{
    protected function getRootNodeName()
    {
        return 'sitemapindex';
    }

    /**
     * Adds a Sitemap URL to the sitemapindex.
     * @param  string $url
     * @param  string $lastModified
     * @return $this
     */
    public function add($url, $lastModified)
    {
        $node = $this->document->createElement('url');
        $node->appendChild(new DOMElement('loc', $this->escapeString($url)));
        $node->appendChild(new DOMElement('lastmod', $this->formatDate($lastModified)));
        $this->rootNode->appendChild($node);

        return $this;
    }
}
