<?php

namespace Scaffold\Essentials\Contracts;

interface AssetInterface
{

    public function getHandle(): string;

    public function getVersion(): string;

    public function getFile(): string;

    public function getData(string $name);

    public function append(string $key, $value): void;
}
