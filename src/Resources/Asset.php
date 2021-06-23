<?php

namespace Scaffold\Essentials\Resources;

use Scaffold\Essentials\Essentials;
use Scaffold\Essentials\Contracts\CacheInterface;
use Scaffold\Essentials\Contracts\AssetInterface;

class Asset implements AssetInterface
{

    private $cache;

    private $handle;

    private $version;

    private $file;

    public function __construct(CacheInterface $cache, Essentials $container, $handle, $file)
    {

        $this->cache = $cache;

        $this->handle = $handle;

        if (! empty($file)) {
            $this->version = filemtime($container->getBasepath($container->getPublicdir() . $file));

            $this->file = $container->getPublicpath($container->getPublicdir() . $file);
        }
    }

    public function getHandle(): string
    {

        return $this->handle;
    }

    public function getVersion(): string
    {

        if (is_null($this->version)) {
            if (! is_null($external = $this->cache->get('external', $this->handle))) {
                preg_match("/(?)\s*((?:[0-9]+\.?)+)/i", $external, $matches);

                if (is_array($matches) && ! empty($matches)) {
                    return $matches[1];
                }

                return substr(md5(openssl_random_pseudo_bytes(20)), -8);
            }
        }

        return $this->version;
    }

    public function getFile(): string
    {

        if (is_null($this->file) && ! is_null($external = $this->cache->get('external', $this->handle))) {
            return $external;
        }

        return $this->file;
    }

    public function getData(string $name)
    {

        return $this->cache->get($name, $this->handle);
    }

    public function append(string $key, $value): void
    {

        $this->cache->set($key, $value, $this->handle);
    }
}
