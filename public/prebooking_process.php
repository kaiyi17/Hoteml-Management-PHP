<?php
session_start();

// Storing data in session
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $_SESSION['prebooking'] = [
    'check_in_date' => $_POST['checkin_date'],
    'check_out_date' => $_POST['checkout_date'],
    'room_type' => $_POST['room_type'],
    'adults' => $_POST['adults'],
    'children' => $_POST['children']
  ];
  header('Location: booking.php');
  exit;
}
