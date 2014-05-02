<?php

use Tackk\Cartographer\AbstractSitemap;

class MockAbstractSitemap extends AbstractSitemap
{
    protected function getRootNodeName()
    {
        return 'urlset';
    }

    protected function getNodeName()
    {
        return 'url';
    }
}

class AbstractSitemapTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Tackk\Cartographer\AbstractSitemap
     */
    protected $abstractMock;

    public function setUp()
    {
        $this->abstractMock = new MockAbstractSitemap();
    }

    public function testFormatDateWithDates()
    {
        $this->assertEquals('2005-01-01T00:00:00+00:00', $this->callProtectedMethod('formatDate', ['2005-01-01']));
        $this->assertEquals('2005-01-01T00:00:01+00:00', $this->callProtectedMethod('formatDate', ['2005-01-01 12:00:01am']));
    }

    public function testFormatDateWithUnixTimestamp()
    {
        $this->assertEquals('2014-04-23T14:14:49+00:00', $this->callProtectedMethod('formatDate', ['1398262489']));
        $this->assertEquals('2014-04-23T14:14:49+00:00', $this->callProtectedMethod('formatDate', [1398262489]));
    }

    public function testFormatDateWithInvalidDate()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->callProtectedMethod('formatDate', ['foo']);
    }

    public function testEscapeString()
    {
        $this->assertEquals('&amp;&apos;&quot;&gt;&lt;', $this->callProtectedMethod('escapeString', ['&\'"><']));
    }

    public function testHasMaxUrlCount()
    {
        $class = new ReflectionClass($this->abstractMock);
        $urlCount = $class->getProperty('urlCount');
        $urlCount->setAccessible(true);

        $urlCount->setValue($this->abstractMock, 49999);
        $this->assertFalse($this->abstractMock->hasMaxUrlCount());

        $urlCount->setValue($this->abstractMock, 50000);
        $this->assertTrue($this->abstractMock->hasMaxUrlCount());
    }

    public function testMaxUrlCount()
    {
        // Pretend we already have 50,000 URLs
        $class = new ReflectionClass($this->abstractMock);
        $urlCount = $class->getProperty('urlCount');
        $urlCount->setAccessible(true);
        $urlCount->setValue($this->abstractMock, AbstractSitemap::MAX_URLS);

        $this->setExpectedException('Tackk\Cartographer\MaxUrlCountExceededException');
        $this->callProtectedMethod('addUrlToDocument', [['loc' => 'http://foo.com']]);
    }

    public function testGetUrlCount()
    {
        // Pretend we already have 50,000 URLs
        $class = new ReflectionClass($this->abstractMock);
        $urlCount = $class->getProperty('urlCount');
        $urlCount->setAccessible(true);
        $urlCount->setValue($this->abstractMock, 22);

        $this->assertEquals(22, $this->abstractMock->getUrlCount());
    }

    protected function callProtectedMethod($name, array $args) {
        $class = new \ReflectionClass($this->abstractMock);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method->invokeArgs($this->abstractMock, $args);
    }
}
