<?php

namespace Essentials\Abstracts;

use Essentials\Essentials;

use Essentials\Services\HookLoader;

abstract class Hooks {

  protected $hooks;

  protected $container;

  public function __construct ( HookLoader $hooks, Essentials $container ) {

    $this->hooks = $hooks;

    $this->container = $container;
  }

  abstract public function register (): void;
}