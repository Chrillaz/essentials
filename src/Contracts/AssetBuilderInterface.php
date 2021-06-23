<?php

namespace Scaffold\Essentials\Contracts;

interface AssetBuilderInterface
{

    public function dequeue(): void;

    public function enqueue(): void;
}
