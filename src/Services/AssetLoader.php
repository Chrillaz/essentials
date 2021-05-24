<?php

namespace Scaffold\Essentials\Services;

use Scaffold\Essentials\Resources\{
  Asset,
  Style,
  Script
};

use Scaffold\Essentials\Abstracts\Loader;

class AssetLoader extends Loader {

  public function addScript ( string $handle, string $file = '' ) {

    $asset = $this->container->makeWith( Asset::class, [
      'handle' => $handle,
      'file'   => $file
    ]);
      
    $script = $this->container->makeWith( Script::class, [
      'asset' => $asset
    ]);

    $this->add( 'assets', $script );

    return $script;
  }

  public function addStyle ( string $handle, string $file = '' ) {

    $asset = $this->container->makeWith( Asset::class, [
      'handle' => $handle,
      'file'   => $file
    ]);

    $style = $this->container->makeWith( Style::class, [
      'asset' => $asset
    ]);

    $this->add( 'assets', $style );

    return $style;
  }

  public function load (): void {

    array_map( function ( $asset ) {

      unset( $asset );
    }, $this->get( 'assets' ) );
  } 
}