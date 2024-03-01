<?php
// src/model/RoomModel.php

function getRoomsFromTextFile($filename) {
    $rooms = [];

    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($roomNumber, $capacity, $rate) = explode(',', $line);
        $rooms[] = [
            'room_number' => $roomNumber,
            'capacity' => $capacity,
            'rate' => $rate
        ];
    }

    return $rooms;
}
