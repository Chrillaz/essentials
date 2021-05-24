<?php

namespace Scaffold\Essentials\Resources;

use Scaffold\Essentials\Contracts\AssetInterface;

use Scaffold\Essentials\Abstracts\AssetBuilder;

final class Style extends AssetBuilder {

  protected $queue;

  protected $asset;

  public function __construct ( \WP_Styles $styles, AssetInterface $asset ) {

    $this->queue = $styles;

    $this->asset = $asset;
  }

  public function enqueue (): void {
    
    if ( ! isset( $this->queue->registered[$this->asset->getHandle()] ) ) {

      $this->queue->add( 
        $this->asset->getHandle(),
        $this->asset->getFile(), 
        $this->asset->getData( 'dependencies' ), 
        $this->asset->getVersion(), 
        $this->asset->getData( 'context' )
      );
    }

    if ( ( $inline = $this->asset->getData( 'inline' ) ) && isset( $this->queue->registered[$this->asset->getHandle()] ) ) {

      $this->queue->add_inline_style( $this->asset->getHandle(), $inline, $this->asset->getData( 'position' ) );
    }

    $this->queue->enqueue( $this->asset->getHandle() );
  }
}