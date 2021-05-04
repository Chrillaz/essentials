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

    $this->runHooks();
  }

  public function registerConfig () {

    array_map( function ( $key, $config ) {

      if ( $key !== 'bindings' ) {
  
        return $this->instance( $key, $config );
      }
    },
      array_keys( $this->getConfig() ),
      $this->getConfig()
    );
  }

  private function bindReusableModules () {

    $this->singleton( Essentials::class, function ( $container ) {

      return $container;
    });

    Util::directoryIterator( $this->getBasepath( '/src/Options' ), function ( $option ) {

      $this->singleton( $option->qualifiedname, function ( $container ) use ( $option ) {

        return $option->qualifiedname::register( $container );
      });
    });

    array_map( function ( $dir ) {

      Util::directoryIterator( $dir, function ( $service ) {
        
        $this->singleton( $service->qualifiedname );
      });
    }, [
      __DIR__ . '/Services',
      $this->getBasepath( '/src/Services' )
    ]);
  }

  private function bindModules () {

    array_map( function ( $interface, $implementation ) {

      if ( $implementation instanceof \Closure ) {

        return $this->bind( $interface, function ( $container ) use ( $implementation ) {

          return $implementation();
        });
      }

      $this->bind( $interface, $implementation );
    },
      array_keys( $this->getConfig()['bindings'] ),
      $this->getConfig()['bindings']
    );
  }

  private function runHooks () {

    array_map( function ( $dir ) {

      Util::directoryIterator( $dir, function ( $hook ) {

        $hook = $this->make( $hook->qualifiedname );

        $hook->register();
      });
    }, [
      $this->getBasepath( '/src/Hooks' )
    ]);
  }

  public function getConfig () {

    if ( ! is_null( $this->config ) ) {

      return $this->config;
    }

    $coreconfig = require __DIR__ . '/Config.php';

    if ( \file_exists( $config = $this->getBasepath( '/src/Config.php' ) ) ) {

      $config = require_once $config;

      foreach ( $config as $key => $value ) {

        if ( isset( $coreconfig[$key] ) ) {

          $coreconfig[$key] = array_merge(
            $coreconfig[$key],
            $value
          );
        } else {

          $coreconfig[$key] = $value;
        }
      }
    }
    
    return $this->config = $coreconfig;
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