<?php

namespace Scaffold\Essentials\Contracts;

interface Fs 
{
    public function getInfo( string $file );

    public function publicDir();

    public function publicPath( string $path = null );

    public function basePath( string $path = null );
}