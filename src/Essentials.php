<?php

namespace Essentials;

use Essentials\Utilities as Util;

use Essentials\Resources\Storage;

use \Illuminate\Container\Container;

class Essentials extends Container {

  protected static $instance;

  protected $repository;

  public function __construct () {

    $this->repository = new Storage();
  }

  public function registerApp ( string $type, string $path ): void {

    $this->repository->set( $type, $path );
  }

  public function getRegisteredApps () {

    return $this->repository->all();
  }

  public function bootstrap () {

    $this->bind( '\\Essentials\\Contracts\\StorageInterface'::class, '\\Essentials\\Resources\\Storage'::class );

    $this->bind( '\\Essentials\\Contracts\\AssetInterface'::class, '\\Essentials\\Resources\\Asset'::class );

    foreach ( $this->getRegisteredApps() as $namespace => $app ) {

      if ( file_exists( $config = $app . '/Config.php' ) ) {

        $config = require $config;

        array_map( function ( $key, $config ) {

          return $this->instance( $key, $config );
        }, 
          array_keys( $config ),
          $config
        );

        /**
         * Bind modules
         */
        array_map( function ( $module, $implementation ) {

          if ( $implementation instanceof \Closure ) {

            return $this->bind( $module, function ( $app ) use ( $implementation ) {

              return $implementation();
            });
          }

          return $this->bind( $module, $implementation );
        }, 
          array_keys( $this['bindings'] ),
          $this['bindings'] 
        );
      }

      if ( Util::dirExists( $app . '/Options' ) ) {

        Util::directoryIterator( $namespace, $app . '/Options', function ( $option ) {
          
          $this->singleton( $option->qualifiedname, function () use ( $option ) {

            return $option->qualifiedname::register( $this );
          });
        });
      }

      if ( Util::dirExists( $app ) ) {

        if ( Util::dirExists( $app . '/Services' ) ) {

          Util::directoryIterator( $namespace, $app . '/Services', function ( $service ) {
  
            $this->singleton( $service->qualifiedname );
          });
        }
        
        array_map( function ( $directory ) use ( $namespace ) {

          if ( Util::dirExists( $directory ) ) {

            Util::directoryIterator( $namespace, $directory, function ( $module ) {
  
              $module = $this->make( $module->qualifiedname );
  
              $module->register();
            });
          }
        }, [
          $app . '/Integrations',
          $app . '/Hooks'
        ]);
      }
    }
  }

  public static function create () {

    if ( is_null( self::$instance ) ) self::$instance = new Essentials();

    return self::$instance;
  }
}