<?php

namespace Scaffold\Essentials\Resources;

use Scaffold\Essentials\Contracts\StorageInterface;

class Storage implements StorageInterface {

  private $storage;

  public function __construct ( array $storage = [] ) {

    $this->storage = $this->flatten( $storage );
  }

  public function contains ( $keyOrValue ): bool {

    return ( array_key_exists( $keyOrValue, $this->storage ) || in_array( $keyOrValue, $this->storage ) );
  }

  public function get ( string $key ) {

    if ( $this->contains( $key ) ) {

      return $this->storage[$key];
    }

    return false;
  }

  public function all (): array {

    return $this->storage;
  }

  public function collect ( ...$keys ): array {

    return array_reduce( $keys, function ( $acc, $curr ) {

      if ( $this->contains( $curr ) ) {

        array_push( $acc, $this->storage[$curr] );
      }

      return $acc;
    }, array() );
  }

  public function set ( string $key, $value ) {

    $this->storage[$key] = $value;
    
    return $this->get( $key );
  }

  public function delete ( string $key ): bool {

    if ( $this->contains( $key ) ) {
      
      unset( $this->storage[$key] );

      return true;
    }

    return false;
  }

  protected function flatten ( array $list ) {

    $flattened = array();

    foreach ( $list as $key => $item ) {

      if ( is_array( $item ) && ! empty( $item ) ) {

        $flattened = array_merge( $flattened, $this->flatten( $item ) );
      } else {

        $flattened[$key] = $item;
      }
    }

    return $flattened;
  }
}