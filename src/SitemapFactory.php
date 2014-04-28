<?php

namespace Tackk\Cartographer;

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
     * Generates the sitemap(s) using the iterator previously set.
     * @param \Iterator $iterator
     * @throws \RuntimeException
     * @return string
     */
    public function create(Iterator $iterator)
    {
        $sitemapList = [];
        $currentSitemap = new Sitemap();
        foreach ($iterator as $entry) {
            list($url, $lastmod, $changefreq, $priority) = $this->parseEntry($entry);
            $currentSitemap->add($url, $lastmod, $changefreq, $priority);

            if ($currentSitemap->getUrlCount() === Sitemap::MAX_URLS) {
                array_push($sitemapList, $currentSitemap);
                $currentSitemap = new Sitemap();
            }
        }

        return $this->writeSitemap($currentSitemap);
    }

    /**
     * Writes the given sitemap to the filesystem.  The filename pattern is:
     * {MD5_Hash}.{Class_Name}.{Index}.xml
     * @param AbstractSitemap $sitemap
     * @return string The filename of the sitemap written
     */
    protected function writeSitemap(AbstractSitemap $sitemap)
    {
        static $index = 0;

        $prefix = $this->randomHash();
        $className = (new \ReflectionClass($sitemap))->getShortName();
        $fileName = "{$prefix}.{$className}.{$index}.xml";
        $this->filesystem->write($fileName, $sitemap->toString());
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
     * Generates a string of random bytes (of given length).
     * @param  integer $bytes The number of bytes to return.
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

        throw new RuntimeException('Extension "openssl" or "mcrpypt" is required, but is not installed.');
    }
}
