<?php
require_once '../../config/database.php';

$booking_id = $_GET['id'] ?? null;

if ($booking_id) {
  $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
  $stmt->execute([$booking_id]);

  header('Location: dashboard.php');
  exit;
} else {
  echo "Invalid booking ID";
}
