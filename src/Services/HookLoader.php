<?php

namespace Scaffold\Essentials\Services;

use Scaffold\Essentials\Abstracts\Loader;

class HookLoader extends Loader
{

    public function addAction(...$args): void
    {

        $this->add('actions', $this->container->make(\Scaffold\Essentials\Contracts\HookInterface::class, [
            'type' => 'action',  
            'args' => $args
        ]));
    }

    public function addFilter(...$args): void
    {

        $this->add('filters', $this->container->make(\Scaffold\Essentials\Contracts\HookInterface::class, [
            'type' => 'filter',
            'args' => $args
        ]));
    }

    public function load(): void
    {

        array_map(function ($hook) {

            'action' === $hook->getType()
            ? \add_action($hook->getAction(), $hook->getCallback(), $hook->getPriority(), $hook->getNumArgs())
            : \add_filter($hook->getAction(), $hook->getCallback(), $hook->getPriority(), $hook->getNumArgs());

            unset($hook);
        }, array_merge(
            ( $this->get('actions') ? $this->get('actions') : [] ),
            ( $this->get('filters') ? $this->get('filters') : [] )
        ));

        $this->clear('actions', 'filters');
    }
}
