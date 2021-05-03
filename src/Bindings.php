<?php

namespace Essentials;

return [
  'bindings' => [
    \WP_Scripts::class, function () { return \wp_scripts(); },
    \WP_Styles::class, function () { return \wp_styles(); },
    \Essentials\Contracts\StorageInterface::class => \Essentials\Resources\Storage::class,
    \Essentials\Contracts\AssetInterface::class => \Essentials\Resources\Asset::class
  ]
];