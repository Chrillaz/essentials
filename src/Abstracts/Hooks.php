<?php

namespace Scaffold\Essentials\Abstracts;

use Scaffold\Essentials\Services\HookLoader;

abstract class Hooks
{

    protected $theme;

    public function __construct($theme)
    {

        $this->theme = $theme;
    }

    abstract public function register(HookLoader $hooks): void;
}
