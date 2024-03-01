<?php

require_once __DIR__ . '/../Bootstrap.php';
require_once __DIR__ . '/../utils/RoomReader.php';


class BookingController
{
  private $twig;

  public function __construct($twig)
  {
    $this->twig = $twig;
  }


  public function handleBookingRequest()
  {


    $errors = [];  // To store error messages

    // Validate and sanitize email
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if (!$email) {
      $errors[] = "Invalid email format.";
    }

    // Sanitize string input
    $name = filter_var($_POST['name']);

    // Validate phone number (assuming numbers only for simplicity)
    $phone = filter_var($_POST['phone'], FILTER_VALIDATE_INT);
    if (!$phone) {
      $errors[] = "Invalid phone number.";
    }

    // Display errors (if any)
    if (!empty($errors)) {
      foreach ($errors as $error) {
        echo "<p>$error</p>";
      }
    } else {
      // Process the form data or store in the database

      // e.g., insert into the database using the BookingModel.php
    }
    echo $this->twig->render('bookingForm.html.twig', ['errors' => $errors, 'rooms' => $rooms]);
  }
}
$controller = new BookingController($twig);
$controller->handleBookingRequest();
