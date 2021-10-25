<?php

namespace Scaffold\Essentials\Services;

use Scaffold\Essentials\Contracts\Fs;

final class FsImplementation implements Fs
{

    private $publicDirectory;

    private $templateDirectory;

    private $templateDirectoryUri;

    private $consumerNamespace;

    public function __construct()
    {

        $this->publicDirectory = '';

        $this->templateDirectory = '';

        $this->templateDirectoryUri = '';
    }

    private function joinPath( Array ...$parts ): string
    {

        return count( $parts ) > 1 ? $parts.join( '' ) : $parts[0];
    }

    private function getFileNamespace( string $file )
    {

        $file = file_get_contents($file);

        if (preg_match('#(namespace)(\\s+)([A-Za-z0-9\\\\]+?)(\\s*);#sm', $file, $matches)) {

            return $matches[3];
        }
    }

    public function getInfo( string $file )
    {

        return \pathinfo( $file );
    }

    public function publicDir()
    {

        return $this->publicDirectory;
    }

    public function publicPath( string $path = null )
    {

        return $this->joinPath( $this->templateDirectoryUri, $path );
    }

    public function basePath( string $path = null )
    {

        return $this->joinPath( $this->templateDirectory, $path );
    }

    public function foreachFile( string $directory, \Closure $callback )
    {

        $paths = [];

        $filter = new \RecursiveCallbackFilterIterator(
            new \RecursiveDirectoryIterator($directory),
            function ($current, $key, $iterator) {

                return ( pathinfo($name = $current->getFileName(), PATHINFO_EXTENSION) === 'php' && $name[0] !== '.' );
            }
        );

        foreach (new \RecursiveIteratorIterator($filter) as $file) {
            $name = $file->getBasename('.php');

            $path = $file->getPath();

            if (empty($paths) || ! \array_key_exists($path, $paths)) {
                $paths[$path] = $this->getFileNamespace($path . '/' . $file->getBasename());
            }

            $namespace = $paths[$path];

            $callback((object) [
                'name' => $name,
                'path' => $path,
                'namespace' => $namespace,
                'qualifiedname' => $namespace . '\\' . $name
            ]);
        }
    }
}