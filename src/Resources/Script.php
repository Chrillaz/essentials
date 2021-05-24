<?php

namespace Scaffold\Essentials\Resources;

use Scaffold\Essentials\Contracts\AssetInterface;

use Scaffold\Essentials\Abstracts\AssetBuilder;

final class Script extends AssetBuilder {

  protected $queue;

  protected $asset;

  public function __construct ( \WP_Scripts $scripts, AssetInterface $asset ) {

    $this->queue = $scripts;

    $this->asset = $asset;
  }

  public function enqueue (): void {

    if ( ! isset( $this->queue->registered[$this->asset->getHandle()] ) ) {

      $this->queue->add( 
        $this->asset->getHandle(), 
        $this->asset->getFile(), 
        $this->asset->getData( 'dependencies' ), 
        $this->asset->getVersion() 
      );

      if ( ! $this->asset->getData( 'context' ) ) {

        $this->queue->add_data( $this->asset->getHandle(), 'group', 1 );
      }
    }

    if ( ( $exec = $this->asset->getData( 'load' ) ) && isset( $this->queue->registered[$this->asset->getHandle()] ) ) {
      
      $this->queue->add_data( $this->asset->getHandle(), 'script_execution', $exec );
    }

    if ( ( $inline = $this->asset->getData( 'inline' ) ) && isset( $this->queue->registered[$this->asset->getHandle()] ) ) {

      $this->queue->add_inline_script( $this->asset->getHandle(), $inline, $this->asset->getData( 'position' ) );
    }

    if ( ( $name = $this->asset->getData( 'objectName' ) ) && isset( $this->queue->registered[$this->asset->getHandle()] ) ) {
      
      $this->queue->localize( $this->asset->getHandle(), $name, $this->asset->getData( 'l10n' ) );
    }

    $this->queue->enqueue( $this->asset->getHandle() );
  }
}