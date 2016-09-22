<?php

namespace Tackk\Cartographer;

use Tackk\Cartographer\News;
use DOMElement;

class SitemapNews extends AbstractSitemap
{
    const SITEMAP_NEWS_NAMESPACE_URI = 'http://www.google.com/schemas/sitemap-news/0.9';

    function __construct()
    {
        parent::__construct();
        $this->addSitemapNewsNS();
    }

    protected function addSitemapNewsNS()
    {
        $this->rootNode->setAttributeNS(
            'http://www.w3.org/2000/xmlns/',
            'xmlns:news',
            self::SITEMAP_NEWS_NAMESPACE_URI);
    }

    protected function getRootNodeName()
    {
        return 'urlset';
    }

    protected function getNodeName()
    {
        return 'url';
    }

    /**
     * Adds a News element to the sitemapnews.
     * @param string $loc
     * @param News $news
     * @return $this
     */
    public function add($loc, News $news)
    {
        $loc = $this->escapeString($loc);
        $node = $this->document->createElement($this->getNodeName());
        $this->rootNode->appendChild($node);
        $node->appendChild(new DOMElement('loc', $loc));
        $node->appendChild($this->getNewsNode($news));
        $this->urlCount++;
        return $this;
    }

    protected function getNewsNode($news)
    {
        $node = $this->createNewsElement('news');
        $node->appendChild($this->getPublicationNode($news));
        $this->appendNewsElements($node, $news);
        return $node;
    }

    protected function getPublicationNode($news)
    {
        $node = $this->createNewsElement('publication');
        $this->appendNewsElementValue($node, 'name', $news->getName());
        $this->appendNewsElementValue($node, 'language', $news->getLanguage());
        return $node;
    }

    protected function appendNewsElements($node, $news)
    {
        $this->appendNewsElementValue($node, 'access', $news->getAccess());
        $this->appendNewsElementValue($node, 'genres', $news->getGenres());
        $this->appendNewsElementValue($node, 'publication_date', $news->getPublicationDate());
        $this->appendNewsElementValue($node, 'title', $news->getTitle());
        $this->appendNewsElementValue($node, 'keywords', $news->getKeywords());
        $this->appendNewsElementValue($node, 'stock_tickers', $news->getStockTickers());
    }

    protected function appendNewsElementValue($node, $name, $value)
    {
        if ($value) {
            $node->appendChild($this->createNewsElement($name, $value));
        }
    }

    protected function createNewsElement($name, $value = NULL)
    {
        return $this->document->createElementNS(
            self::SITEMAP_NEWS_NAMESPACE_URI, 'news:' . $name, $value);
    }
}