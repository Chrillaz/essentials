<?php

namespace Essentials;

use Essentials\Utilities as Util;

use \Illuminate\Container\Container;

class Essentials extends Container {

  protected $namespace;
  
  protected $basepath;

  protected $baseuri;

  public function __construct ( array $args ) {

    extract( $args );

    $this->namespace = isset( $namespace ) ? $namespace : '';
    
    $this->basepath = isset( $basepath ) ? $basepath : '';

    $this->baseuri = isset( $assetpath ) ? $assetpath : '';

    $this->bindModules();
  }

  private function bindModules () {

    $bindings = require __DIR__ . '/Bindings.php';

    if ( \file_exists( $config = $this->getBasepath( '/src/Config.php' ) ) ) {

      $config = require $config;

      if ( isset( $config['bindings'] ) ) {

        $bindings['bindings'] = array_merge(
          $config['bindings'],
          $bindings['bindings']
        );
      }
    }

    array_map( function ( $interface, $implementation ) {

      if ( $implementation instanceof \Closure ) {

        return $this->bind( $interface, function ( $container ) use ( $implementation ) {

          return $implementation();
        });
      }

      $this->bind( $interface, $implementation );
    },
      array_keys( $bindings['bindings'] ),
      $bindings['bindings']
    );
  }

  public function setConfig ( array $config ) {

    array_map( function ( $key, $config ) {

      if ( $key !== 'bindings' ) {

        return $this->instance( $key, $config );
      }
    },
      array_keys( $config ),
      $config
    );
  }

  public function getBasepath ( string $relpath = null ) {

    if ( ! is_null( $relpath ) ) {

      return $this->basepath . $relpath;
    }
    return $this->basepath;
  }

  public function getBaseuri ( string $relpath = null ) {

    if ( ! is_null( $relpath ) ) {

      return $this->baseuri . $relpath;
    }

    return $this->baseuri;
  }

  public function getNamespace () {

    return $this->namespace;
  }

  public static function create ( ...$args ) {

    return new Essentials( ...$args );
  }
}