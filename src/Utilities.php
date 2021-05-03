<?php

namespace Essentials;

class Utilities {

  public static function directoryIterator ( string $namespace, string $path, \Closure $callback ) {

    $filter = new \RecursiveCallbackFilterIterator( 
      new \RecursiveDirectoryIterator( $path ), 
      function ( $current, $key, $iterator ) {

        return ( pathinfo( $name = $current->getFileName(), PATHINFO_EXTENSION ) && $name[0] !== '.' );
      }
    );

    foreach ( new \RecursiveIteratorIterator( $filter ) as $info) {

      $name = $info->getBasename( '.php' );

      $parts = explode( 'src/', $path = $info->getPath() );

      $namespace = $namespace . '\\' . end( $parts );

      $qualifiedname = $namespace . '\\' . $name . ''::class;

      $callback( (object) [
        'name' => $name,
        'path' => $path,
        'namespace' => $namespace,
        'qualifiedname' => $qualifiedname
      ]);
    }
  }

  public static function dirExists ( string $path ) {

    return ( \realpath( $path ) !== false && is_dir( $path ) );
  }
}