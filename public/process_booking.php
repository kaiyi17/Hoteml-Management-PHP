<?php

define('PROJECT_ROOT', realpath(dirname(__FILE__, 2)));

require_once '../vendor/autoload.php';
require_once '../config/database.php';
require_once '../src/model/Database.php';
require_once "../src/model/BookingModel.php";
require_once '../src/Bootstrap.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['form_data'] = $_POST;
}

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$check_in_date = trim($_POST['check_in_date']);
$check_out_date = trim($_POST['check_out_date']);
$adults = intval($_POST['Adults']);
$children = intval($_POST['Children']);
$selectedRoomType = trim($_POST['room_type']);

$errors = [];



if (empty($name)) {
    $errors[] = "Name is required.";
}

if (empty($email)) {
    $errors[] = "Email is required.";
}

if (empty($phone)) {
    $errors[] = "Phone is required.";
}
if (empty($check_in_date)) {
    $errors[] = "Check_in_date is required.";
}
if (empty($check_out_date)) {
    $errors[] = "Check_out_date is required.";
}
if ($adults <= 0) {
    $errors[] = "Adults number must be a positive value.";
}

if ($children < 0) {
    $errors[] = "Children number cannot be negative.";
}

$checkinDate = new DateTime($check_in_date);
$checkoutDate = new DateTime($check_out_date);
if ($checkoutDate <= $checkinDate) {
    $errors[] = "Check-out date should be after check-in date.";
}

// check availbale room types
$query = "SELECT * FROM RoomType WHERE maxAdults >= $adults AND maxChildren >= $children AND AvailableRooms > 0";
$result = $pdo->query($query);

$availableRoomTypes = [];
while ($row = $result->fetch()) {
    $availableRoomTypes[] = $row;
}

if (empty($availableRoomTypes)) {
    $errors[] = "Sorry, no rooms available for the selected dates and guests number.";
}


$stmt = $pdo->prepare("SELECT id FROM customers WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
    $customer = $stmt->fetch();
    $customer_id = $customer['id'];
} else {
    $stmt = $pdo->prepare("INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $phone]);
    $customer_id = $pdo->lastInsertId();
}


$query = "
    SELECT rooms.RoomNumber 
    FROM rooms 
    JOIN roomtype ON rooms.TypeID = roomtype.TypeID
    WHERE roomtype.TypeName = ? AND rooms.RoomNumber NOT IN (
        SELECT room_bookings.room_number 
        FROM room_bookings 
        JOIN bookings ON room_bookings.booking_id = bookings.id 
        WHERE ? BETWEEN check_in_date AND check_out_date 
        OR ? BETWEEN check_in_date AND check_out_date
    ) 
    LIMIT 1
";
$stmt = $pdo->prepare($query);
$stmt->execute([$selectedRoomType, $check_in_date, $check_out_date]);

if ($room = $stmt->fetch()) {
    $roomNumber = $room['RoomNumber'];

    $stmt = $pdo->prepare("INSERT INTO bookings (customer_id, check_in_date, check_out_date) VALUES (?, ?, ?)");
    $stmt->execute([$customer_id, $check_in_date, $check_out_date]);
    $booking_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO room_bookings (booking_id, room_number) VALUES (?, ?)");
    $stmt->execute([$booking_id, $roomNumber]);

    echo "Booking successfully created!";
} else {
    $_SESSION['form_data'] = [
        'check_in_date' => $_POST['check_in_date'],
        'check_out_date' => $_POST['check_out_date'],
        'room_type' => $_POST['room_type'],
        'adults' => $_POST['Adults'],
        'children' => $_POST['Children']
    ];
    echo "Sorry, the selected type are fully booked for the chosen dates. Please select a different type of room, or differnet date.";
    echo "<a href='booking.php'>Return to reservation page</a>";
    exit();
}

header('Location: confirmation.php');
exit();
