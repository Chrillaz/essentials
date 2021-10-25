<?php

namespace Scaffold\Essentials\Resources;

use Scaffold\Essentials\Contracts\Hook;

final class HookImplementation implements Hook
{

    protected $type;

    protected $event;

    protected $callback;

    protected $priority;

    protected $numargs;

    public function __construct(string $type, array $args)
    {

        list ( $event, $callback, $component, $priority, $numargs ) = array_pad($args, 5, null);

        if ($event === '') {
            return $this;
        }

        $this->type = $type;

        $this->event = $event;

        $this->callback = ( is_object($component) ? [$component, $callback] : $callback );

        if (is_int($component)) {
            $this->priority = $component;

            $this->numargs = $priority;
        } else {
            $this->priority = ( $priority === null ? 10 : $priority );

            $this->numargs = ( $numargs === null ? 1 : $numargs );
        }
    }

    public function getType(): string
    {

        return $this->type;
    }

    public function getAction(): string
    {

        return $this->event;
    }

    public function getCallback()
    {

        return $this->callback;
    }

    public function getPriority(): int
    {

        return $this->priority;
    }

    public function getNumArgs(): int
    {

        return $this->numargs;
    }
}