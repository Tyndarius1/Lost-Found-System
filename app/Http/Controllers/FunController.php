<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class FunController extends Controller
{
    public function index(Request $request)
    {
        // Default coordinates (Manila)
        $lat = 14.5995;
        $lon = 120.9842;
        $locationName = "Manila, PH";

        // If user searched for a city
        if ($request->has('city')) {
            // Use Geocoding API to turn city name into coordinates
            $geoResponse = Http::withoutVerifying()->get('https://geocoding-api.open-meteo.com/v1/search', [
                'name' => $request->city,
                'count' => 1
            ]);

            if ($geoResponse->successful() && isset($geoResponse->json()['results'][0])) {
                $result = $geoResponse->json()['results'][0];
                $lat = $result['latitude'];
                $lon = $result['longitude'];
                $locationName = $result['name'] . ', ' . ($result['country'] ?? '');
            }
        }

        $response = Http::withoutVerifying()->get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $lat,
            'longitude' => $lon,
            'current_weather' => true,
        ]);

        $weather = $response->json();
        
        // Logic for Dynamic Backgrounds based on weather code
        $weatherCode = $weather['current_weather']['weathercode'] ?? 0;
        $condition = $this->getConditionName($weatherCode);

        return view('fun.index', compact('weather', 'locationName', 'condition'));
    }

    private function getConditionName($code) {
        if ($code == 0) return 'clear';
        if (in_array($code, [1, 2, 3])) return 'cloudy';
        if (in_array($code, [45, 48])) return 'foggy';
        if (in_array($code, [51, 53, 55, 61, 63, 65, 80, 81, 82])) return 'rainy';
        return 'clear';
    }
}
