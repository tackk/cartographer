<?php

namespace Tackk\Cartographer;

use DOMDocument;
use DOMElement;

class Sitemap
{
    /**
     * @var string
     */
    protected $xmlVersion = '1.0';

    /**
     * @var string
     */
    protected $xmlEncoding = 'UTF-8';

    /**
     * @var DOMDocument
     */
    protected $document;

    /**
     * @var DOMElement
     */
    protected $urlSet;

    /**
     * Sets up the sitemap XML document and urlset node.
     */
    public function __construct()
    {
        $this->document = new DOMDocument($this->xmlVersion, $this->xmlEncoding);
        $this->document->formatOutput = true;
        $this->urlSet = $this->document->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'urlset');
    }

    /**
     * Adds a URL to the urlset.
     * @param  string $url
     * @param  string $lastModified
     * @param  string $changeFrequency
     * @param  float  $priority
     * @return $this
     */
    public function addUrl($url, $lastModified, $changeFrequency, $priority)
    {
        $node = $this->document->createElement('url');
        $node->appendChild(new DOMElement('loc', $this->escapeString($url)));
        $node->appendChild(new DOMElement('lastmod', $lastModified));
        $node->appendChild(new DOMElement('changefreq', $changeFrequency));
        $node->appendChild(new DOMElement('priority', $priority));
        $this->urlSet->appendChild($node);

        return $this;
    }

    /**
     * Converts the Sitemap to an XML string.
     * @return string
     */
    public function toString()
    {
        return (string) $this;
    }

    /**
     * Converts the Sitemap to an XML string.
     * @return string
     */
    public function __toString()
    {
        $this->document->appendChild($this->urlSet);
        return $this->document->saveXML();
    }

    /**
     * Escapes a string so it can be inserted into the Sitemap
     * @param  string $string The string to escape.
     * @return string
     */
    protected function escapeString($string)
    {
        $from = ['&', '\'', '"', '>', '<'];
        $to = ['&amp;', '&apos;', '&quot;', '&gt;', '&lt;'];
        return str_replace($from, $to, $string);
    }
}
