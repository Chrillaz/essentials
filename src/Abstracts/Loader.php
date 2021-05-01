<?php

namespace Essentials\Abstracts;

use Essentials\Essentials;

use Essentials\Contracts\StorageInterface;

use Essentials\Contracts\LoaderInterface;

abstract class Loader implements LoaderInterface {

  protected $queue;
  
  protected $app;

  public function __construct ( StorageInterface $storage, Essentials $app ) {

    $this->queue = $storage;

    $this->app = $app;
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