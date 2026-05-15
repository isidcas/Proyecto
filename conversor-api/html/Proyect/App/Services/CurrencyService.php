<?php
namespace App\Services;

class CurrencyService {
    public function getConversion($amount, $from, $to) {
        $url = "https://api.frankfurter.app/latest?amount=$amount&from=$from&to=$to";
        $content = @file_get_contents($url);
        if (!$content) return null;
        $data = json_decode($content, true);
        return [
            'result' => $data['rates'][$to],
            'rate' => $data['rates'][$to] / $amount
        ];
    }
}