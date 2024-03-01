<?php
// src/model/WeatherModel.php

class WeatherModel
{
    private $apiKey = "8ad2bceae928ba5ec481740f3c0710e1";

    public function getWeather($city)
    {
        $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$this->apiKey}";

        $response = file_get_contents($apiUrl);
        $weatherData = json_decode($response, true);

        return $weatherData;
    }
}
