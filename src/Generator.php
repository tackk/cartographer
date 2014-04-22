<?php

namespace Tackk\Cartographer;

use League\Flysystem\FilesystemInterface;

class Generator
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

    public function generate()
    {
        // pass
    }

} 
