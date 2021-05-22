<?php

namespace Essentials\Abstracts;

use Essentials\Contracts\AssetBuilderInterface;

abstract class AssetBuilder implements AssetBuilderInterface {

  public function external ( string $path ) {

    $this->asset->append( 'external', trim( $path ) );

    return $this;
  }
  
  public function dependencies ( ...$dependencies ) {

    $this->asset->append( 'dependencies', $dependencies );

    return $this;
  }

  public function inline ( string $inline, string $position = 'after' ) {

    $this->asset->append( 'inline', $inline );

    $this->asset->append( 'position', $position );

    return $this;
  }

  public function context ( string $context ) {

    $this->asset->append( 'context', [$context] );

    return $this;
  }

  public function localize ( string $objectName, array $l10n ) {

    $this->asset->append( 'objectName', $objectName );

    $this->asset->append( 'l10n', $l10n );

    return $this;
  }

  public function load ( string $name ) {

    $name = trim( $name );

    if ( in_array( $name, ['defer', 'async'] ) ) {

      $this->asset->append( 'load', $name );
    }

    return $this;
  }

  public function dequeue (): void {

    $this->queue->dequeue( $this->asset->getHandle() );
  }

  abstract public function enqueue (): void;
}