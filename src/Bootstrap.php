<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Detect the server protocol
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

// Detect the server host
$server = $_SERVER['SERVER_NAME'];

// Detect the URI path
$path = "/finalproject/hotel-reservation/public/";

// Combine to get the base URL
$base_url = $protocol . $server . $path;

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

// Add base_url as a global variable to the Twig environment
$twig->addGlobal('base_url', $base_url);
