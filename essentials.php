<?php

namespace Essentials;

use Essentials\Utilities as Util;

use \Illuminate\Container\Container;

class Essentials extends Container {

  protected $config;

  protected $namespace;
  
  protected $basepath;

  protected $publicpath;

  public function __construct ( array $args ) {
    
    extract( $args );

    $this->basepath = isset( $basepath ) ? $basepath : '';

    $this->publicpath = isset( $publicpath ) ? $publicpath : '';

    $this->getNamespace();

    $this->registerConfig();

    $this->bindReusableModules();

    $this->bindModules();
  }

  public function registerConfig () {

    foreach ( $this->getConfig() as $key => $config ) {

      $key !== 'bindings' ? $this->instance( $key, $config ) : null;
    }
  }

  private function bindReusableModules () {

    $this->singleton( Essentials::class, function ( $container ) {

      return $container;
    });

    Util::directoryIterator( __DIR__ . '/Services', function ( $service ) {
        
      $this->singleton( $service->qualifiedname );
    });
  }

  private function bindModules () {

    foreach ( $this->getConfig()['bindings'] as $interface => $implementation ) {

      if ( $implementation instanceof \Closure ) {

        $this->bind( $interface, function ( $container ) use ( $implementation ) {

          return $implementation();
        });
      }

      $this->bind( $interface, $implementation );
    }
  }

  public function getConfig () {

    if ( ! is_null( $this->config ) ) {

      return $this->config;
    }

    $config = require __DIR__ . '/Config.php';

    if ( \file_exists( $appconfig = $this->getBasepath( '/src/Config.php' ) ) ) {

      $appconfig = require_once $appconfig;

      foreach ( $appconfig as $key => $value ) {

        if ( isset( $config[$key] ) ) {

          $config[$key] = array_merge(
            $config[$key],
            $value
          );
        } else {

          $config[$key] = $value;
        }
      }
    }
    
    return $this->config = $config;
  }

  public function getBasepath ( string $relpath = null ) {

    return is_null( $relpath ) ? $this->basepath : $this->basepath . $relpath;
  }

  public function getPublicpath ( string $relpath = null ) {

    return is_null( $relpath ) ? $this->publicpath : $this->publicpath . $relpath;
  }

  public function getNamespace () {

    if ( ! is_null( $this->namespace ) ) {

      return $this->namespace;
    }

    $composer = \json_decode( \file_get_contents( $this->getBasepath( '/composer.json' ) ), true );

    if ( isset( $composer['autoload'] ) && isset( $composer['autoload']['psr-4'] ) ) {
      
      foreach ( $composer['autoload']['psr-4'] as $namespace => $path ) {
        
        if ( 'src/' === strtolower( $path ) ) {

          return $this->namespace = $namespace;
        }
      }
    }
  }

  public static function create ( ...$args ) {

    return new Self( ...$args );
  }
}