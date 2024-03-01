<?php

define('PROJECT_ROOT', realpath(dirname(__FILE__, 3)));

require_once '../../vendor/autoload.php';
require_once '../../src/model/BookingModel.php';
require_once PROJECT_ROOT . '/config/database.php';

$base_url = "http://localhost/finalproject/hotel-reservation/public/";


$loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__, 2) . '/templates');
$twig = new \Twig\Environment($loader);


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$whereConditions = "";
$joinStatements = "FROM bookings 
JOIN customers ON bookings.customer_id = customers.id
JOIN room_bookings ON bookings.id = room_bookings.booking_id";


$search_name = $_GET['search_name'] ?? null;
$search_check_in_date = $_GET['search_check_in_date'] ?? null;
$search_check_out_date = $_GET['search_check_out_date'] ?? null;
$today = $_GET['today'] ?? null;
$page = $_GET['page'] ?? 1;

$query = "SELECT 
    bookings.id as booking_id,
    bookings.customer_id as booking_customer_id,
    bookings.check_in_date,
    bookings.check_out_date,
    room_bookings.room_number,
    customers.id as customer_id,
    customers.name,
    customers.email,
    customers.phone
" . $joinStatements;

$params = [];

if ($search_name) {
  $whereConditions .= ($whereConditions ? " AND " : " WHERE ") . "customers.name LIKE :search_name";
  $params[':search_name'] = "%$search_name%";
}
if ($search_check_in_date) {
  $formatted_search_check_in_date = date('Y-m-d', strtotime($search_check_in_date));
  $whereConditions .= ($whereConditions ? " AND " : " WHERE ") . "bookings.check_in_date = :search_check_in_date";
  $params[':search_check_in_date'] = $formatted_search_check_in_date;
}
if ($search_check_out_date) {
  $formatted_search_check_out_date = date('Y-m-d', strtotime($search_check_out_date));
  $whereConditions .= ($whereConditions ? " AND " : " WHERE ") . "bookings.check_out_date = :search_check_out_date";
  $params[':search_check_out_date'] = $formatted_search_check_out_date;
}
if ($today) {
  $whereConditions .= ($whereConditions ? " AND " : " WHERE ") . "bookings.check_in_date = CURDATE()";
}

$query .= $whereConditions;

$query .= " ORDER BY bookings.check_in_date DESC";


// For counting total bookings
$countQuery = "SELECT COUNT(*) as total " . $joinStatements . " " . (count($params) ? str_replace("WHERE", "AND", strstr($query, "WHERE")) : "");

$countStmt = $pdo->prepare($countQuery);
foreach ($params as $key => $value) {
  $countStmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$countStmt->execute();
$total_bookings = $countStmt->fetchColumn();

$limit = 6;
$total_pages = ceil($total_bookings / $limit);
$start = ($page - 1) * $limit;

// Query with LIMIT for pagination
$queryWithLimit = $query . " LIMIT :start, :limit";
$paramsWithLimit = $params;
$paramsWithLimit[':start'] = $start;
$paramsWithLimit[':limit'] = $limit;

$stmt = $pdo->prepare($queryWithLimit);
foreach ($paramsWithLimit as $key => $value) {
  $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$stmt->execute();
$bookingsWithLimit = $stmt->fetchAll();

// Passing necessary data to the Twig template
echo $twig->render('dashboard.html.twig', [
  'base_url' => $base_url,
  'bookings' => $bookingsWithLimit,
  'current_page' => $page,
  'total_pages' => $total_pages
]);
