<?php

namespace Scaffold\Contracts;

interface CacheInterface {
  
  public function get ( string $key, string $group = 'default' );

  public function collect ( array $keys, string $group = 'default' );
  
  public function set ( string $key, $value, string $group = 'default' ): bool;
  
  public function delete ( string $key, string $group = 'default' ): bool;
  
  public function persist ( string $key, $value, $exp = DAY_IN_SECONDS ): bool;
}