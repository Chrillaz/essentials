<?php

namespace Scaffold\Essentials;

return [
  'bindings' => [
    \WP_Scripts::class => function () {
        return \wp_scripts();
    },
    \WP_Styles::class => function () {
        return \wp_styles();
    },
    \WP_Object_Cache::class => function () {
        global $wp_object_cache;
        return $wp_object_cache;
    },
    \Scaffold\Essentials\Contracts\Fs::class => \Scaffold\Essentials\Services\FsImplementation::class,
    \Scaffold\Essentials\Contracts\Cache::class => \Scaffold\Essentials\Services\CacheImplementation::class,
    \Scaffold\Essentials\Contracts\Asset::class => \Scaffold\Essentials\Resources\AssetImplementation::class,
    \Scaffold\Essentials\Contracts\Hook::class => \Scaffold\Essentials\Resources\HookImplementation::class
  ]
];