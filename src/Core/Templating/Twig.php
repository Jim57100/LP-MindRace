<?php

namespace Framework\Templating;

use Framework\Config\Config;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twig
{
    protected Environment $templating;

    public function __construct()
    {
        $basePath = dirname(__DIR__) . '/../..';

        $loader = new FilesystemLoader($basePath . '/templates');

        $params = [];

        if (Config::get('APP_ENV') === 'prod') {
            $params['cache'] = $basePath . '/var/cache/templates';
        }

        $this->templating = new Environment($loader, $params);
    }

    public function render(string $template, array $args = []): string
    {
        $args['alerts'] = $_SESSION['alert'] ?? [];
        unset($_SESSION['alert']);
        return $this->templating->render($template, $args);
    }
}
