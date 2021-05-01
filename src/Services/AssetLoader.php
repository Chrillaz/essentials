<?php

namespace Essentials\Services;

use Essentials\Resources\{
  Asset,
  Style,
  Script
};

use Essentials\Abstracts\Loader;

class AssetLoader extends Loader {

  public function addScript ( string $handle, string $file = '' ) {

    $asset = $this->app->makeWith( Asset::class, [
      'handle' => $handle,
      'file'   => $file
    ]);
      
    $script = $this->app->makeWith( Script::class, [
      'asset' => $asset
    ]);

    $this->add( 'assets', $script );

    return $script;
  }

  public function addStyle ( string $handle, string $file = '' ) {

    $asset = $this->app->makeWith( Asset::class, [
      'handle' => $handle,
      'file'   => $file
    ]);

    $style = $this->app->makeWith( Style::class, [
      'asset' => $asset
    ]);

    $this->add( 'assets', $style );

    return $style;
  }

  public function load (): void {

    array_map( function ( $asset ) {

      unset( $asset );
    }, $this->queue->get( 'assets' ) );

    $this->reset();
  } 
}