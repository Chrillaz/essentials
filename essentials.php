<?php

/**
 * Plugin Name: Essentials
 * Author: Christoffer Öhman
 */

require __DIR__ . '/vendor/autoload.php';

$essentials = \Essentials\Essentials::create();

$essentials->singleton( \Essentials\Essentials::class, function ( $app ) {

  return $app;
});
