<?php

namespace Scaffold\Essentials\Abstracts;

use Scaffold\Essentials\Essentials;

use Scaffold\Essentials\Contracts\StorageInterface;

use Scaffold\Essentials\Contracts\LoaderInterface;

abstract class Loader implements LoaderInterface {

  protected $queue;
  
  protected $container;

  public function __construct ( StorageInterface $storage, Essentials $container ) {

    $this->queue = $storage;

    $this->container = $container;
  }

  protected function add ( string $queue, $value ): void {

    if ( ! $this->queue->contains( $queue ) ) {

      $this->queue->set( $queue, [] );
    }

    $queued = $this->queue->get( $queue );

    array_push( $queued, $value );

    $this->queue->set( $queue, $queued );
  }

  protected function reset (): void {

    foreach ( $this->queue->all() as $key => $value ) {
      
      $this->queue->delete( $key );
    }
  }

  abstract public function load (): void;
}