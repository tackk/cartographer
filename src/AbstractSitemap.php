<?php

namespace Tackk\Cartographer;

use DateTime;
use DateTimeZone;
use DOMDocument;
use DOMElement;
use InvalidArgumentException;

abstract class AbstractSitemap
{
    /**
     * Get the root node name for the sitemap (e.g. 'urlset').
     * @return string
     */
    abstract protected function getRootNodeName();

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
     * @var DOMDocument
     */
    protected $document;

    /**
     * @var DOMElement
     */
    protected $rootNode;

    /**
     * @var bool
     */
    protected $isFrozen = false;

    /**
     * Sets up the sitemap XML document and urlset node.
     */
    public function __construct()
    {
        $this->document = new DOMDocument($this->xmlVersion, $this->xmlEncoding);
        $this->document->formatOutput = true;
        $this->rootNode = $this->document->createElementNS($this->xmlNamespaceUri, $this->getRootNodeName());
    }

    /**
     * Freeze the sitemap, and append the rootNode to the document.
     */
    public function freeze()
    {
        $this->document->appendChild($this->rootNode);
        $this->isFrozen = true;
    }

    public function isFrozen()
    {
        return $this->isFrozen;
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
        if (! $this->isFrozen()) {
            $this->freeze();
        }

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

    /**
     * Takes a date as a string (or int in the case of a unix timestamp).
     * @param  string $dateString
     * @return string
     * @throws InvalidArgumentException
     */
    protected function formatDate($dateString)
    {
        try {
            // We have to handle timestamps a little differently
            if (is_numeric($dateString) && (int) $dateString == $dateString) {
                $date = DateTime::createFromFormat('U', (int) $dateString, new DateTimeZone('UTC'));
            } else {
                $date = new DateTime($dateString, new DateTimeZone('UTC'));
            }
            return $date->format(DateTime::W3C);
        } catch (\Exception $e) {
            throw new InvalidArgumentException("Malformed last modified date: {$dateString}", 0, $e);
        }
    }
}
