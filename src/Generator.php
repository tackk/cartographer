<?php

namespace Tackk\Cartographer;

use Iterator;
use League\Flysystem\FilesystemInterface;
use RuntimeException;

class Generator
{
    /**
     * @var FilesystemInterface
     */
    protected $filesystem = null;

    /**
     * @var Iterator
     */
    protected $iterator;

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
     * Set the Iterator to use.
     * @param Iterator $iterator
     */
    public function setIterator(Iterator $iterator)
    {
        $this->iterator = $iterator;
    }

    public function generate()
    {
        if (is_null($this->iterator)) {
            throw new RuntimeException('An Iterator must be set before generation.');
        }

        $sitemap = new Sitemap();
        foreach ($this->iterator as $entry) {
            list($url, $lastmod, $changefreq, $priority) = $this->parseEntry($entry);
            $sitemap->add($url, $lastmod, $changefreq, $priority);
        }

        return $sitemap->toString();
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
}
