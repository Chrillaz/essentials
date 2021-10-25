<?php

namespace Scaffold\Essentials\Entities;

use Scaffold\Essentials\Contracts\Fs;

final class Asset
{

    private $handle;
    
    private $path;
    
    private $version;

    public function __construct( Fs $fs, string $handle, string $relPath )
    {

        $this->handle = $handle;

        $this->path = $fs->publicPath( $fs->publicDir() . $relPath );

        $this->version = $this->setVersion( $fs, $relPath );
    }

    protected function setVersion( Fs $fs, string $relPath ): int
    {
        
        return filemtime( $fs->basePath( $fs->publicDir() . $relPath ) );
    }

    public function getHandle(): string
    {

        return $this->handle;
    }

    public function getPath(): string
    {

        return $this->path;
    }

    public function getVersion(): int
    {

        return $this->version;
    }
}