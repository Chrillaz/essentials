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
    \Scaffold\Essentials\Contracts\CacheInterface::class => \Scaffold\Essentials\Services\Cache::class,
    \Scaffold\Essentials\Contracts\AssetInterface::class => \Scaffold\Essentials\Resources\Asset::class,
    \Scaffold\Essentials\Contracts\HookInterface::class => \Scaffold\Essentials\Resources\Hook::class
  ]
];