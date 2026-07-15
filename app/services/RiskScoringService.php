<?php

namespace App\Services;

class RiskScoringService
{
    public function calculate(
        $weather,
        $inflation,
        $sentiment,
        $geoRisk,
        $exchangeRate
    ) {
        $score = 0;

        // WEATHER
        $weather = strtolower($weather);

        if (
            str_contains($weather, 'storm') ||
            str_contains($weather, 'thunder')
        ) {
            $score += 25;
        } elseif (
            str_contains($weather, 'rain')
        ) {
            $score += 15;
        }

        // INFLATION
        if ($inflation >= 10) {
            $score += 20;
        } elseif ($inflation >= 5) {
            $score += 10;
        }

        // NEWS SENTIMENT
        if ($sentiment == 'negative') {
            $score += 25;
        } elseif ($sentiment == 'neutral') {
            $score += 10;
        }

        // GEOPOLITICAL
        if ($geoRisk == 'HIGH') {
            $score += 25;
        } elseif ($geoRisk == 'MEDIUM') {
            $score += 15;
        }

        // EXCHANGE RATE
        if ($exchangeRate >= 17000) {
            $score += 10;
        }

        return min($score, 100);
    }

    public function status($score)
    {
        if ($score >= 80) {
            return 'HIGH';
        }

        if ($score >= 50) {
            return 'MEDIUM';
        }

        return 'LOW';
    }
}