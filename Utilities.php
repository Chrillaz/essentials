<?php

namespace Essentials;

class Utilities {

  public static function directoryIterator ( string $path, \Closure $callback ) {

    $filter = new \RecursiveCallbackFilterIterator( 
      new \RecursiveDirectoryIterator( $path ), 
      function ( $current, $key, $iterator ) {
        
        return ( pathinfo( $name = $current->getFileName(), PATHINFO_EXTENSION ) === 'php' && $name[0] !== '.' );
      }
    );

    foreach ( new \RecursiveIteratorIterator( $filter ) as $info) {

      $name = $info->getBasename( '.php' );
      
      $namespace = self::getNamespace( $info->getPath() . '/' . $info->getBasename() );

      $callback( (object) [
        'name' => $name,
        'path' => $info->getPath(),
        'namespace' => $namespace,
        'qualifiedname' => $namespace . '\\' . $name
      ]);
    }
  }

  public static function getNamespace ( string $src ) {

    $src = file_get_contents( $src );

    if ( preg_match( '#^namespace\s+(.+?);$#sm', $src, $matches ) ) {
        
      return $matches[1];
    }
  }

  public static function dirExists ( string $path ) {

    return ( \realpath( $path ) !== false && is_dir( $path ) );
  }
}