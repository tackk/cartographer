<?php

use Tackk\Cartographer\AbstractSitemap;

class MockAbstractSitemap extends AbstractSitemap
{
    protected function getRootNodeName()
    {
        return 'urlset';
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

    public function testMaxUrlCount()
    {
        $this->setExpectedException('Tackk\Cartographer\MaxUrlCountExceededException');

        for ($i = 0; $i < AbstractSitemap::MAX_URLS + 1; $i++) {
            $this->callProtectedMethod('addUrlToDocument', [['loc' => 'http://foo.com']]);
        }
    }

    protected function callProtectedMethod($name, array $args) {
        $class = new \ReflectionClass($this->abstractMock);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method->invokeArgs($this->abstractMock, $args);
    }
}
