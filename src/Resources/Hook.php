<?php

namespace Scaffold\Essentials\Resources;

use Scaffold\Essentials\Contracts\HookInterface;

final class Hook implements HookInterface {

  private $event;

  private $callback;

  private $priority;

  private $numargs;

  public function __construct ( array $args ) {

    list ( $event, $callback, $component, $priority, $numargs ) = array_pad( $args, 5, null );
    
    if ( $event === '' ) return $this;

    $this->event = $event;

    $this->callback = ( is_object( $component ) ? [$component, $callback] : $callback );

    if ( is_int( $component ) ) {

      $this->priority = $component;

      $this->numargs = $priority;
    } else {

      $this->priority = ( $priority === null ? 10 : $priority );
  
      $this->numargs = ( $numargs === null ? 1 : $numargs );
    }
  }

  public function getAction (): string {

    return $this->event;
  }

  public function getCallback () {

    return $this->callback;
  }

  public function getPriority (): int {

    return $this->priority;
  }

  public function getNumArgs (): int {

    return $this->numargs;
  }
}