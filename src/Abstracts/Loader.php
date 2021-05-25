<?php

namespace Scaffold\Essentials\Abstracts;

use Scaffold\Essentials\Essentials;

use Scaffold\Essentials\Contracts\CacheInterface;

use Scaffold\Essentials\Contracts\LoaderInterface;

abstract class Loader implements LoaderInterface {

  protected $queue;
  
  protected $container;

  protected $group = 'loadergroup';

  public function __construct ( CacheInterface $cache, Essentials $container ) {

    $this->queue = $cache;

    $this->container = $container;
  }

  protected function get ( string $key ) {

    return $this->queue->get( $key, $this->group );
  }

  protected function add ( string $queue, $value ): void {

    if ( ! $this->queue->get( $queue, $this->group ) ) {

      $this->queue->set( $queue, array($value), $this->group );
    }

    $queued = $this->queue->get( $queue, $this->group );

    if ( ! in_array( $value, $queued ) ) {

      array_push( $queued, $value );
    }

    $this->queue->set( $queue, $queued, $this->group );
  }

  protected function clear ( ...$queues ) {

    foreach ( $queues as $queue ) {

      $this->queue->delete( $queue, $this->group );
    }
  }

  abstract public function load (): void;
}