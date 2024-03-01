<?php
define('PROJECT_ROOT', realpath(dirname(__FILE__)));

require_once '../vendor/autoload.php';
require_once '../config/database.php';
require_once '../src/model/Database.php';
require_once '../src/model/weatherModel.php';

$base_url = "http://localhost/finalproject/hotel-reservation/public/";


session_start();

$prebooking_data = $_SESSION['prebooking'] ?? [
  'check_in_date' => '',
  'check_out_date' => '',
  'room_type' => '',
  'adults' => '',
  'children' => ''
];

$form_data = $_SESSION['form_data'] ?? [];
$booking_data = array_merge($prebooking_data, $form_data);
unset($_SESSION['form_data']);

// Set up Twig environment
$loader = new \Twig\Loader\FilesystemLoader('../templates');
$twig = new \Twig\Environment($loader);

$weatherModel = new WeatherModel();
$weatherInfo = $weatherModel->getWeather("Montreal");

$temperature = round($weatherInfo['main']['temp'] - 273.15);
$weatherDescription = ucfirst($weatherInfo['weather'][0]['description']);
$weatherIcon = $weatherInfo['weather'][0]['icon'];
$currentDate = date("d/m/Y");

// Get rooms from the database
$rooms = [];
$stmt = $pdo->prepare("SELECT rooms.RoomNumber, roomtype.TypeName, roomtype.Price, roomtype.Image, roomtype.Description, roomtype.maxAdults, roomtype.maxChildren
                       FROM rooms 
                       JOIN roomtype ON rooms.TypeID = roomtype.TypeID
                       WHERE rooms.RoomNumber IN (
                           SELECT MIN(rooms.RoomNumber)
                           FROM rooms
                           GROUP BY rooms.TypeID
                       )");
$stmt->execute();

while ($row = $stmt->fetch()) {
  $rooms[] = [
    'number' => $row['RoomNumber'],
    'type' => $row['TypeName'],
    'rate' => sprintf('$%s', $row['Price']),
    'image' => $row['Image'],
    'description' => $row['Description'],
    'maxAdults' => $row['maxAdults'],
    'maxChildren' => $row['maxChildren'],
  ];
}

$data = array_merge($booking_data, [
  'weather' => [
    'temperature' => $temperature,
    'description' => $weatherDescription,
    'icon' => $weatherIcon,
    'date' => $currentDate
  ],
  'rooms' => $rooms,
  'base_url' => $base_url,
]);

echo $twig->render('bookingForm.html.twig', $data);
