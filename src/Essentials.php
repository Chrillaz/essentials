<?php

namespace Essentials;

use Essentials\Utilities as Util;

use \Illuminate\Container\Container;

class Essentials extends Container {

  protected $basepath;

  protected $namespace;

  public function __construct ( string $namespace, string $basepath ) {

    $this->basepath = $basepath;

    $this->namespace = $namespace;

    $this->bindModules();
  }

  private function bindModules () {

    $bindings = require __DIR__ . '/Bindings.php';

    if ( \file_exists( $config = $this->getBasepath() . '/Config.php' ) ) {

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

  public function getBasepath () {

    return $this->basepath;
  }

  public function getNamespace () {

    return $this->namespace;
  }

  public static function create ( ...$args ) {

    return new Essentials( ...$args );
  }
}