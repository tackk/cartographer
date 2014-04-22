<?php

class SitemapTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Cartographer\Sitemap
     */
    protected $sitemap;

    public function setUp()
    {
        $this->sitemap = new Cartographer\Sitemap();
    }

    public function testCanInstantiate()
    {
        $this->assertInstanceOf('Cartographer\Sitemap', $this->sitemap);
    }
}
