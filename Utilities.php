<?php

namespace Essentials;

class Utilities {

  public static function directoryIterator ( string $path, \Closure $callback ) {

    $paths = [];

    $filter = new \RecursiveCallbackFilterIterator( 
      new \RecursiveDirectoryIterator( $path ), 
      function ( $current, $key, $iterator ) {
        
        return ( pathinfo( $name = $current->getFileName(), PATHINFO_EXTENSION ) === 'php' && $name[0] !== '.' );
      }
    );

    foreach ( new \RecursiveIteratorIterator( $filter ) as $info ) {

      $name = $info->getBasename( '.php' );

      $path = $info->getPath();
      
      if ( empty( $paths ) || ! \array_key_exists( $path, $paths ) ) {

        $paths[$path] = self::getNamespace( $path . '/' . $info->getBasename() );
      }

      $namespace = $paths[$path];

      $callback( (object) [
        'name' => $name,
        'path' => $path,
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