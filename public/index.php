<?php

define('PROJECT_ROOT', realpath(dirname(__FILE__)));

require_once '../vendor/autoload.php';
require_once '../config/database.php';
require_once '../src/model/Database.php';
require_once '../src/Bootstrap.php';


// Set up Twig environment
$loader = new \Twig\Loader\FilesystemLoader('../templates');
$twig = new \Twig\Environment($loader);

$check_in_date = isset($check_in_date) ? $check_in_date : '';
$check_out_date = isset($check_out_date) ? $check_out_date : '';
$room_type = isset($room_type) ? $room_type : '';
$adult = isset($adult) ? $adult : '';
$children = isset($children) ? $children : '';

$_SESSION['prebooking'] = [
  'check_in_date' => $check_in_date,
  'check_out_date' => $check_out_date,
  'room_type' => $room_type,
  'adults' => $adult,
  'children' => $children,
];


$carousel_images = [
  ['path' => 'images/lobby11.png', 'title' => 'Urban Nest', 'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae, repellendus?'],
  ['path' => 'images/lobby22.png', 'title' => 'Urban Nest', 'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae, repellendus?'],
  ['path' => 'images/lobby33.png', 'title' => 'Urban Nest', 'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae, repellendus?'],
  ['path' => 'images/lobby44.png', 'title' => 'Urban Nest', 'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae, repellendus?'],
];

$data = [
  'carousel_images' => $carousel_images,
  'base_url' => $base_url,
];

echo $twig->render('index.html.twig', $data);
