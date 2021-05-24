<?php

namespace Scaffold\Essentials\Services;

use Scaffold\Essentials\Contracts\CacheInterface;

class Cache implements CacheInterface {

  protected $cache;

  public function __construct( \WP_Object_Cache $cache ) {

    $this->cache = $cache;
  }

  public function get ( string $key, string $group = 'default' ) {

    if ( $cache = $this->cache->get( $key, $group ) ) {

      return $cache;
    }

    if ( 'persistent' === $group && $transient = \get_transient( $key ) ) {

      return $transient;
    }

    return false;
  }

  public function collect ( array $keys, string $group = 'default' ) {

    return $this->cache->get_multiple( $keys, $group );
  }

  public function set ( string $key, $value, string $group = 'default' ): bool {

    return $this->cache->set( $key, $value, $group );
  }

  public function delete ( string $key, string $group = 'default' ): bool {

    if ( $cache = $this->cache->delete( $key ) ) {

      return $cache;
    }

    if ( 'persistent' === $group && $transient = \delete_transient( $key ) ) {

      return $transient;
    }

    return false;
  }

  public function flush () {

    $this->cache->flush();
  }

  public function persist ( string $key, $value, $exp = DAY_IN_SECONDS ): bool {

    return \set_transient( $key, $value, $exp );
  }
}