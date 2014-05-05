<?php

namespace Tackk\Cartographer;

use ArrayObject;
use DateTime;
use Iterator;
use League\Flysystem\FilesystemInterface;
use RuntimeException;

class SitemapFactory
{
    /**
     * @var FilesystemInterface
     */
    protected $filesystem = null;

    /**
     * @var string
     */
    protected $baseUrl = '';

    /**
     * @var array
     */
    protected $filesCreated = [];

    /**
     * @param FilesystemInterface $filesystem
     */
    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Gets the Filesystem.
     * @return FilesystemInterface
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * Sets the Base URL for sitemap files.
     *
     * @param  string $baseUrl
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');

        return $this;
    }

    /**
     * Gets the Base URL for sitemap files.
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Gets the array of files created.
     * @return array
     */
    public function getFilesCreated()
    {
        return $this->filesCreated;
    }

    /**
     * Generates the sitemap(s) using the iterator previously set.
     * @param \Iterator $iterator
     * @throws \RuntimeException
     * @return string The URL for the entry Sitemap
     */
    public function createSitemap(Iterator $iterator)
    {
        $groupName = $this->randomHash();
        $paths = new ArrayObject();
        $sitemap = new Sitemap();
        foreach ($iterator as $entry) {
            if ($sitemap->hasMaxUrlCount()) {
                $paths->append($this->writeSitemap($groupName, $sitemap));
                $sitemap = new Sitemap();
            }

            list($url, $lastmod, $changefreq, $priority) = $this->parseEntry($entry);
            $sitemap->add($url, $lastmod, $changefreq, $priority);
        }
        $paths->append($this->writeSitemap($groupName, $sitemap));

        if ($paths->count() > 1) {
            return $this->createSitemapIndex($paths->getIterator());
        }

        return $this->fileUrl($paths[0]);
    }

    /**
     * Creates a Sitemap index given an Iterator of Sitemaps
     * @param Iterator $sitemaps
     * @return mixed
     */
    public function createSitemapIndex(Iterator $sitemaps)
    {
        $groupName = $this->randomHash();
        $sitemapIndexes = new ArrayObject();
        $sitemapIndex = new SitemapIndex();
        $lastmod = date(DateTime::W3C);
        foreach ($sitemaps as $sitemapPath) {
            // Ignoring because this is an edge case for HUGE sites...like Facebook.
            // @codeCoverageIgnoreStart
            if ($sitemapIndex->hasMaxUrlCount()) {
                $sitemapIndexes->append($this->writeSitemap($groupName, $sitemapIndex));
                $sitemapIndex = new SitemapIndex();
            }
            // @codeCoverageIgnoreEnd

            $sitemapIndex->add($this->fileUrl($sitemapPath), $lastmod);
        }
        $sitemapIndexes->append($this->writeSitemap($groupName, $sitemapIndex));

        // This will probably never happen, as it would mean over 2.5 Billion URLs in the
        // sitemap.  So unless Facebook uses this library, this will never happen, so ignore
        // it from code coverage.
        // @codeCoverageIgnoreStart
        if ($sitemapIndexes->count() > 1) {
            return $this->createSitemapIndex($sitemapIndexes->getIterator());
        }
        // @codeCoverageIgnoreEnd

        return $this->fileUrl($sitemapIndexes[0]);
    }

    /**
     * Writes the given sitemap to the filesystem.  The filename pattern is:
     * {MD5_Hash}.{Class_Name}.{Index}.xml
     * @param string          $groupName
     * @param AbstractSitemap $sitemap
     * @return string The filename of the sitemap written
     */
    protected function writeSitemap($groupName, AbstractSitemap $sitemap)
    {
        static $index = 0;

        $className = (new \ReflectionClass($sitemap))->getShortName();
        $fileName = "{$groupName}.{$className}.{$index}.xml";
        $this->filesystem->write($fileName, $sitemap->toString());
        array_push($this->filesCreated, $fileName);
        $index++;

        return $fileName;
    }

    /**
     * Parses the given Entry into its constituent parts.
     * @param  mixed $entry The entry to parse
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function parseEntry($entry)
    {
        if (!get_property($entry, 'url')) {
            throw new \InvalidArgumentException('Url is missing or not accessible.');
        }
        $url        = get_property($entry, 'url');
        $lastmod    = get_property($entry, 'lastmod');
        $changefreq = get_property($entry, 'changefreq');
        $priority   = get_property($entry, 'priority');

        return [$url, $lastmod, $changefreq, $priority];
    }

    /**
     * Generates a random MD5 hash.
     * @return string
     * @throws \RuntimeException
     */
    protected function randomHash()
    {
        return md5($this->randomBytes(32));
    }

    /**
     * Gets the Full URL for the given file.
     * @param  string $file
     * @return string
     */
    protected function fileUrl($file)
    {
        return $this->baseUrl.'/'.ltrim($file, '/');
    }

    /**
     * Generates a string of random bytes (of given length).
     * @param  integer $bytes The number of bytes to return.
     * @throws \RuntimeException
     * @return string
     * @codeCoverageIgnore
     */
    protected function randomBytes($bytes = 32)
    {
        if (extension_loaded('openssl')) {
            return openssl_random_pseudo_bytes($bytes);
        } elseif (extension_loaded('mcrypt')) {
            return mcrypt_create_iv($bytes, MCRYPT_DEV_URANDOM);
        }

        throw new RuntimeException('Extension "openssl" or "mcrypt" is required, but is not installed.');
    }
}
