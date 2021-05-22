<?php

namespace Essentials\Services;

use Essentials\Resources\Hook;

use Essentials\Abstracts\Loader;

class HookLoader extends Loader {

  public function addAction ( ...$args ): void {
    
    $this->add( 'actions', $this->container->make( hook::class, [
      'args' => $args 
    ]));
  }

  public function addFilter ( ...$args ): void {

    $this->add( 'filters', $this->container->make( hook::class, [
      'args' => $args 
    ]));
  }

  public function load (): void {

    if ( $actions = $this->queue->get( 'actions' ) ) {

      array_map( function ( $hook ) {
        
        \add_action( $hook->getAction(), $hook->getCallback(), $hook->getPriority(), $hook->getNumArgs() );
  
        unset( $hook );
      }, $actions );
    }

    if ( $filters = $this->queue->get( 'filters' ) ) {

      array_map( function ( $hook ) {
  
        \add_filter( $hook->getAction(), $hook->getCallback(), $hook->getPriority(), $hook->getNumArgs() );
  
        unset( $hook );
      }, $filters );
    }

    $this->reset();
  }
}