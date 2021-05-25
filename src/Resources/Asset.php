<?php

namespace Scaffold\Essentials\Resources;

use Scaffold\Essentials\Essentials;

use Scaffold\Essentials\Contracts\CacheInterface;

use Scaffold\Essentials\Contracts\AssetInterface;

class Asset implements AssetInterface {

  private $data;

  private $handle;

  private $version;

  private $file;

  public function __construct ( CacheInterface $data, Essentials $container, $handle, $file ) {

    $this->data = $data;

    $this->handle = $handle;

    if ( ! empty( $file ) ) {
      
      $this->version = filemtime( $container->getBasepath( '/assets' . $file ) );
      
      $this->file = $container->getPublicpath( '/assets' . $file );
    }
  }

  public function getHandle (): string {

    return $this->handle;
  }

  public function getVersion (): string {

    if ( is_null( $this->version ) ) {

      if ( ! is_null( $external = $this->data->get( 'external', $this->handle ) ) ) {

        preg_match( "/(?)\s*((?:[0-9]+\.?)+)/i", $external, $matches );

        if ( is_array( $matches ) && ! empty( $matches ) ) {

          return $matches[1];
        }
      }
    }

    return $this->version;
  }

  public function getFile (): string {

    if ( is_null( $this->file ) && ! is_null( $external = $this->data->get( 'external', $this->handle ) ) ) {

      return $external;
    }

    return $this->file;
  }

  public function getData ( string $name ) {

    return $this->data->get( $name, $this->handle );
  }

  public function append ( string $key, $value ): void {
        
    $this->data->set( $key, $value, $this->handle );  
  }
}