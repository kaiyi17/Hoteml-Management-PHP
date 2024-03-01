<?php

define('PROJECT_ROOT', realpath(dirname(__FILE__, 3)));

require_once '../../vendor/autoload.php';

$base_url = "http://localhost/finalproject/hotel-reservation/public/";

$loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__, 2) . '/templates');
$twig = new \Twig\Environment($loader);

$error = null;
if (isset($_GET['error'])) {
  $error = "Invalid username or password!";
}

echo $twig->render('adminLogin.html.twig', ['error' => $error, 'base_url' => $base_url]);
