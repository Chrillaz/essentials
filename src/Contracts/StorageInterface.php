<?php

namespace Essentials\Contracts;

interface StorageInterface {

  /**
   * Checks wether storage contains given value, could be either key or value
   * 
   * @param mixed $value
   * 
   * @return bool
   */
  public function contains ( $value ): bool;

  /**
   * Retrieve value by given key, returns false if value doesn't exist
   * 
   * @param string $key
   * 
   * @return mixed|false
   */
  public function get ( string $key );

  /**
   * Returns the entire storage array
   * 
   * @return array
   */
  public function all (): array;

  /**
   * Collect values by key from storage
   * 
   * @param array $keys
   * 
   * @return array
   */
  public function collect ( ...$keys ): array;

  /**
   * Set value by given key, returns false if value can't be updated
   * 
   * @param string $key
   * 
   * @param mixed $value
   * 
   * @return mixed|false
   */
  public function set ( string $key, $value );

  /**
   * Unsets value by given key from storage
   * 
   * @param string $key
   * 
   * @return bool
   */
  public function delete ( string $key ): bool;
}