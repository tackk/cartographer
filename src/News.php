<?php

namespace Tackk\Cartographer;

class News
{
    protected $name;
    protected $language;
    protected $access;
    protected $genres;
    protected $publicationDate;
    protected $title;
    protected $keywords;
    protected $stockTickers;

    function getName()
    {
        return $this->name;
    }

    function getLanguage()
    {
        return $this->language;
    }

    function getAccess()
    {
        return $this->access;
    }

    function getGenres()
    {
        return $this->genres;
    }

    function getPublicationDate()
    {
        return $this->publicationDate;
    }

    function getTitle()
    {
        return $this->title;
    }

    function getKeywords()
    {
        return $this->keywords;
    }

    function getStockTickers()
    {
        return $this->stockTickers;
    }

    function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    function setAccess($access)
    {
        $this->access = $access;
        return $this;
    }

    function setGenres($genres)
    {
        $this->genres = $genres;
        return $this;
    }

    function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;
        return $this;
    }

    function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    function setKeywords($keywords)
    {
        $this->keywords = $keywords;
        return $this;
    }

    function setStockTickers($stockTickers)
    {
        $this->stockTickers = $stockTickers;
        return $this;
    }
}