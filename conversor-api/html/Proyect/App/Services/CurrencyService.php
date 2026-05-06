<?php

namespace App\Services;

class CurrencyService {

    public function convert(
        $origen,
        $destino,
        $cantidad
    ){

        $url = "https://api.exchangerate-api.com/v4/latest/$origen";

        $response = file_get_contents($url);

        $data = json_decode($response,true);

        $rate = $data['rates'][$destino];

        $resultado = $cantidad * $rate;

        return [
            'resultado' => $resultado,
            'tasa' => $rate
        ];
    }
}