<?php

namespace Scaffold\Essentials\Resources;

use Scaffold\Essentials\Contracts\AssetInterface;
use Scaffold\Essentials\Abstracts\AssetBuilder;

final class Style extends AssetBuilder
{

    protected $queue;

    protected $asset;

    public function __construct(\WP_Styles $styles, AssetInterface $asset)
    {

        $this->queue = $styles;

        $this->asset = $asset;
    }

    public function enqueue(): void
    {

        if (! isset($this->queue->registered[$this->asset->getHandle()])) {
            $media = $this->asset->getData('context');

            $this->queue->add(
                $this->asset->getHandle(),
                $this->asset->getFile(),
                $this->asset->getData('dependencies'),
                $this->asset->getVersion(),
                ( ! $media ? 'all' : $media )
            );
        }

        if (isset($this->queue->registered[$this->asset->getHandle()])) {
            if (! $this->asset->getData('load') && $inline = $this->asset->getData('inline')) {
                $this->queue->add_inline_style(
                    $this->asset->getHandle(),
                    $inline,
                    $this->asset->getData('position')
                );
            }

            if ($exec = $this->asset->getData('load')) {
                $this->queue->add_data(
                    $this->asset->getHandle(),
                    'script_execution',
                    $exec
                );
            }
        }

        $this->queue->enqueue($this->asset->getHandle());
    }
}