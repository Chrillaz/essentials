<?php

namespace Essentials\Abstracts;

use Essentials\Essentials;

use Essentials\Services\HookLoader;

abstract class Hooks {

  protected $hooks;

  protected $app;

  public function __construct ( HookLoader $hooks, Essentials $app ) {

    $this->hooks = $hooks;

    $this->app = $app;
  }

  abstract public function register (): void;
}