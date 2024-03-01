<?php
$data = json_decode(file_get_contents('php://input'), true);
$roomNumber = $data['roomNumber'];

$sql = "SELECT roomTypeId FROM rooms WHERE roomNumber = :roomNumber";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':roomNumber', $roomNumber);
$stmt->execute();
$roomTypeId = $stmt->fetchColumn();

$sqlCheckAvailability = "SELECT AvailableRooms FROM roomtype WHERE id = :roomTypeId";
$stmtCheck = $pdo->prepare($sqlCheckAvailability);
$stmtCheck->bindParam(':roomTypeId', $roomTypeId);
$stmtCheck->execute();
$availableRooms = $stmtCheck->fetchColumn();

if ($availableRooms > 0) {
  $sqlUpdate = "UPDATE roomtype SET AvailableRooms = AvailableRooms - 1 WHERE id = :roomTypeId AND AvailableRooms > 0";
  $stmtUpdate = $pdo->prepare($sqlUpdate);
  $stmtUpdate->bindParam(':roomTypeId', $roomTypeId);
  $stmtUpdate->execute();

  $response = ['success' => true];
} else {
  $response = ['success' => false, 'message' => 'No rooms available'];
}

header('Content-Type: application/json');
echo json_encode($response);
