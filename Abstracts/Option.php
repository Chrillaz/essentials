<?php

namespace Essentials\Abstracts;

use \Essentials\Essentials;

use \Essentials\Contracts\OptionInterface;

abstract class Option implements OptionInterface {

  protected $name;

  protected $capability;

  protected $default;

  public function __construct( string $name, string $capability, $default ) {
  
    $this->name = $name;

    $this->capability = $capability;

    $this->default = $default;
  }

  public function getName (): string {

    return $this->name;
  }

  public function getDefault () {

    return $this->default;
  }

  public function getCapability (): string {

    return $this->capability;
  }

  public function getOption () {

    return \get_option( $this->getName(), $this->getDefault() );
  }

  public function get ( string $key = null ) {

    if ( is_null( $key ) ) {

      return $this->getOption();
    }

    if ( ! is_array( $option = $this->getOption() ) ) {

      throw new \Exception( 'Option is not array' );
    }
    
    if ( array_key_exists( $key, $option ) ) {
      
      return \esc_attr( $option[$key] );
    }

    return false;
  }

  public function set ( string $key, $value ) {

    if ( ! is_array( $option = $this->getOption() ) ) {

      return \update_option( $this->getName(), $value );
    }

    if ( array_key_exists( $key, $option ) ) {

      $option[$key] = $value;

      return \update_option( $this->getName(), $option );
    }

    throw new \Exception( 'Option not set' );
  }

  public function remove ( string $key ) {

    if ( $key === $this->getName() ) {

      return \delete_option( $this->getName() );
    }

    if ( is_array( $option = $this->getOption() ) && array_key_exists( $key, $option ) ) {

      unset( $option[$key] );

      return \update_option( $this->getName(), $option );
    }
  }

  abstract public static function register ( Essentials $container ): OptionInterface;
}