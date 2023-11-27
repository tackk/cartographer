<?php

namespace Tackk\Cartographer;

use Tackk\Cartographer\Exception\MaxUrlCountExceededException;

abstract class AbstractSitemap
{
    public const MAX_URLS = 50000;

    /**
     * Get the root node name for the sitemap (e.g. 'urlset').
     */
    abstract protected function getRootNodeName(): string;

    /**
     * Get the node name for the sitemap (e.g. 'url').
     */
    abstract protected function getNodeName(): string;

    /**
     * @var string
     */
    protected $xmlVersion = '1.0';

    /**
     * @var string
     */
    protected $xmlEncoding = 'UTF-8';

    /**
     * @var string
     */
    protected $xmlNamespaceUri = 'http://www.sitemaps.org/schemas/sitemap/0.9';

    /**
     * @var \DOMDocument
     */
    protected $document;

    /**
     * @var \DOMElement
     */
    protected $rootNode;

    /**
     * @var bool
     */
    protected $isFrozen = false;

    /**
     * @var int
     */
    protected $urlCount = 0;

    /**
     * Sets up the sitemap XML document and urlset node.
     */
    public function __construct()
    {
        $this->document = new \DOMDocument($this->xmlVersion, $this->xmlEncoding);
        $this->rootNode = $this->document->createElementNS($this->xmlNamespaceUri, $this->getRootNodeName());

        // Make the output Pretty
        $this->document->formatOutput = true;
    }

    /**
     * Freeze the sitemap, and append the rootNode to the document.
     */
    public function freeze(): void
    {
        $this->document->appendChild($this->rootNode);
        $this->isFrozen = true;
    }

    public function isFrozen(): bool
    {
        return $this->isFrozen;
    }

    /**
     * Gets the number of Urls in the sitemap.
     */
    public function getUrlCount(): int
    {
        return $this->urlCount;
    }

    /**
     * Checks if the sitemap contains the maximum URL count.
     */
    public function hasMaxUrlCount(): bool
    {
        return $this->urlCount === static::MAX_URLS;
    }

    /**
     * Converts the Sitemap to an XML string.
     */
    public function toString(): string
    {
        return (string) $this;
    }

    /**
     * Converts the Sitemap to an XML string.
     */
    public function __toString(): string
    {
        if (!$this->isFrozen()) {
            $this->freeze();
        }

        return $this->document->saveXML();
    }

    /**
     * Adds a URL to the document with the given array of elements.
     *
     * @throws MaxUrlCountExceededException
     */
    protected function addUrlToDocument(array $urlArray): self
    {
        if ($this->hasMaxUrlCount()) {
            throw new MaxUrlCountExceededException('Maximum number of URLs has been reached, cannot add more.');
        }

        $node = $this->document->createElement($this->getNodeName());

        foreach ($urlArray as $key => $value) {
            if (is_null($value)) {
                continue;
            }
            $node->appendChild(new \DOMElement($key, $value));
        }
        $this->rootNode->appendChild($node);
        $this->urlCount++;

        return $this;
    }

    /**
     * Escapes a string so it can be inserted into the Sitemap
     * @param  string $string The string to escape.
     */
    protected function escapeString(string $string): string
    {
        $from = ['&', '\'', '"', '>', '<'];
        $to   = ['&amp;', '&apos;', '&quot;', '&gt;', '&lt;'];

        return str_replace($from, $to, $string);
    }

    /**
     * Takes a date as a string (or int in the case of a unix timestamp).
     *
     * @throws \InvalidArgumentException
     */
    protected function formatDate(string $dateString): string
    {
        try {
            // We have to handle timestamps a little differently
            if (is_numeric($dateString) && (int) $dateString == $dateString) {
                $date = \DateTime::createFromFormat('U', (int) $dateString, new \DateTimeZone('UTC'));
            } else {
                $date = new \DateTime($dateString, new \DateTimeZone('UTC'));
            }

            return $date->format(\DateTimeInterface::W3C);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException("Malformed last modified date: {$dateString}", 0, $e);
        }
    }
}
