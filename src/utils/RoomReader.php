<?php

define('PROJECT_ROOT', realpath(dirname(__FILE__, 2)));
require_once '../vendor/autoload.php';
require_once '../config/database.php';
require_once '../src/model/Database.php';
require_once "../src/model/BookingModel.php";

function readRoomsFromDatabase($pdo)
{
  $rooms = [];

  $sql = "SELECT r.room_number, rt.type_name, rt.rate, rt.description, rt.image, rt.max_adults, rt.max_children 
            FROM rooms r
            JOIN room_types rt ON r.room_type_id = rt.id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $rooms[] = [
      'number' => $row['room_number'],
      'type' => $row['type_name'],
      'rate' => $row['rate'],
      'description' => $row['description'],
      'image' => $row['image'],
      'max_adults' => $row['max_adults'],
      'max_children' => $row['max_children']
    ];
  }

  return $rooms;
}
