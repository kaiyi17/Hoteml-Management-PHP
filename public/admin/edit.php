<?php
require_once '../../config/database.php';

$booking_id = $_GET['id'] ?? null;

$stmt = $pdo->prepare("SELECT * FROM bookings JOIN customers ON bookings.customer_id = customers.id WHERE bookings.id = ?");
$stmt->execute([$booking_id]);
$booking = $stmt->fetch();

if (!$booking) {
  die('Booking not found!');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $check_in_date = $_POST['check_in_date'];
  $check_out_date = $_POST['check_out_date'];

  $stmt = $pdo->prepare("UPDATE customers SET name = ?, email = ?, phone = ? WHERE id = ?");
  $stmt->execute([$name, $email, $phone, $booking['customer_id']]);

  $stmt = $pdo->prepare("UPDATE bookings SET check_in_date = ?, check_out_date = ? WHERE id = ?");
  $stmt->execute([$check_in_date, $check_out_date, $booking_id]);

  header('Location: dashboard.php');
  exit;
}
?>

<form method="post">
  Name: <input type="text" name="name" value="<?php echo $booking['name']; ?>">
  Email: <input type="email" name="email" value="<?php echo $booking['email']; ?>">
  Phone: <input type="text" name="phone" value="<?php echo $booking['phone']; ?>">
  Check-in Date: <input type="date" name="check_in_date" value="<?php echo $booking['check_in_date']; ?>">
  Check-out Date: <input type="date" name="check_out_date" value="<?php echo $booking['check_out_date']; ?>">
  <input type="submit" value="Save Changes">
</form>