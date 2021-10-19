<?php

namespace Scaffold\Essentials\Contracts;

interface HookInterface
{

    public function getType(): string;

    public function getAction(): string;

    public function getCallback();

    public function getPriority(): int;

    public function getNumArgs(): int;
}