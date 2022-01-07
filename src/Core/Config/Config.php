<?php

namespace Framework\Config;

class Config
{
    public static function get(string $name, $default = null)
    {
        $name = strtoupper($name);

        $configBase = require(dirname(__DIR__) . '/../../config/app.php');

        $configOverloadFile = dirname(__DIR__) . '/../../config/app.local.php';
        $configOverload = [];
        if (file_exists($configOverloadFile)) {
            $configOverload = require($configOverloadFile);
        }

        $config = $configOverload + $configBase;

        return $config[$name] ?? $default;
    }
}
