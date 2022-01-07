<?php

namespace Framework\Controller;

use Framework\Templating\Twig;
// session_start();

abstract class AbstractController
{
    public function render(string $template, array $args = []): string
    {
        $twig = new Twig();

        return $twig->render($template, $args);
    }

    public function isPost() :bool
    {
        !empty($_POST);
        return strtoupper($_SERVER['REQUEST_METHOD']) === 'POST';
        //create user inside db
    }

    public function redirect(string $uri) :void
    {
        header('Location:'.$uri.'');
        exit();
    }

}
