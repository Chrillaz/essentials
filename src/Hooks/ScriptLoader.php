<?php

namespace Scaffold\Essentials\Hooks;

use Scaffold\Essentials\Abstracts\Hooks;
use Scaffold\Essentials\Services\HookLoader;

final class ScriptLoader extends Hooks
{

    protected $scripts;

    protected $styles;

    protected $critical = [];

    public function __construct(\WP_Scripts $scripts, \WP_Styles $styles)
    {

        $this->scripts = $scripts;

        $this->styles = $styles;
    }

    protected function executionType(string $handle, string $type, $queue)
    {

        $execute = $queue->get_data($handle, $type);

        if (! $execute) {
            return $execute;
        }

        foreach ($queue->registered as $asset) {
            if (in_array($handle, $asset->deps, true)) {
                return false;
            }
        }

        return $execute;
    }

    public function criticalExecution()
    {

        foreach ($this->critical as $path) {
            echo '<style type="text/css">' . \file_get_contents($path) . '</style>';
        }
    }

    public function scriptExecution(string $tag, string $handle)
    {

        if (! $exec = $this->executionType($handle, 'script_execution', $this->scripts)) {
            return $tag;
        }

        if (! preg_match(":\s$exec(=|>|\s):", $tag)) {
            $tag = preg_replace(':(?=></script>):', " $exec", $tag, 1);
        }

        return $tag;
    }

    public function styleExecution(string $tag, string $handle, string $src)
    {

        if (! $exec = $this->executionType($handle, 'script_execution', $this->styles)) {
            return $tag;
        }

        if ('critical' === $exec) {
            $src = explode('?', $src);

            $this->critical[] = ABSPATH . parse_url($src[0])['path'];

            return;
        }

        return sprintf(
            '<link id="%s" rel="preload" href="%s" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">
            <noscript>%s</noscript>',
            $handle,
            $src,
            $tag
        );
    }

    public function register(HookLoader $hooks): void
    {

        $hooks->addFilter('script_loader_tag', 'scriptExecution', $this, 10, 2);

        $hooks->addFilter('style_loader_tag', 'styleExecution', $this, 10, 3);

        $hooks->addAction('wp_head', 'criticalExecution', $this);

        $hooks->load();
    }
}