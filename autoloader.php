<?php

namespace Charm;

/**
 * Require plugin classes on demand.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
spl_autoload_register(function ($class) {

    // Remove prefix from namespace:
    // Charm\Foo\Bar => Foo\Bar
    $class = substr($class, strlen(__NAMESPACE__ . '\\'));

    // Convert namespace to path:
    // Foo\Bar => Foo/Bar
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    // Append PHP file extension:
    // Foo/Bar => Foo/Bar.php
    $class = $class . '.php';

    // Prepend plugin directory:
    // Foo/Bar.php => plugin/Foo/Bar.php
    $class = 'plugin/' . $class;

    // Prepend absolute path of current directory:
    // plugin/Foo/Bar.php => <root>/wp-content/mu-plugins/charm/plugin/Foo/Bar.php
    $class = dirname(__FILE__) . '/' . $class;

    // If file does not exist, exit
    if (!file_exists($class)) return;

    // Require class:
    // /wp-content/mu-plugins/charm/plugin/Foo/Bar.php
    require_once $class;
});