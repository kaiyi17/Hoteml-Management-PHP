<?php

require_once PROJECT_ROOT . '/config/database.php';

class BookingModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function createBooking($customer_id, $check_in_date, $check_out_date, $room_number)
    {
        $query = "INSERT INTO bookings (customer_id, check_in_date, check_out_date, room_number) VALUES (:customer_id, :check_in_date, :check_out_date, :room_number)";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(":customer_id", $customer_id);
        $stmt->bindParam(":check_in_date", $check_in_date);
        $stmt->bindParam(":check_out_date", $check_out_date);
        $stmt->bindParam(":room_number", $room_number);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
