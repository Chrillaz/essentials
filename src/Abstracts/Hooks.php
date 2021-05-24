<?php

namespace Scaffold\Essentials\Abstracts;

use Scaffold\Essentials\Essentials;

use Scaffold\Essentials\Services\HookLoader;

abstract class Hooks {

  protected $hooks;

  protected $container;

  public function __construct ( HookLoader $hooks, Essentials $container ) {

    $this->hooks = $hooks;

    $this->container = $container;
  }

  abstract public function register (): void;
}