<?php

namespace App\Services;

use App\Models\PositiveWord;
use App\Models\NegativeWord;

class SentimentService
{
    public function analyze($text)
    {
        $text = strtolower($text);

        $words = preg_split('/\W+/', $text);

        $positiveWords = PositiveWord::pluck('word')->toArray();
        $negativeWords = NegativeWord::pluck('word')->toArray();

        $positive = 0;
        $negative = 0;
        $neutral = 0;

        foreach ($words as $word) {

            if (trim($word) == '') {
                continue;
            }

            if (in_array($word, $positiveWords)) {

                $positive++;

            } elseif (in_array($word, $negativeWords)) {

                $negative++;

            } else {

                $neutral++;

            }
        }

        $total = $positive + $negative + $neutral;

        if ($total == 0) {
            $total = 1;
        }

        $positivePercent = round(($positive / $total) * 100, 2);

        $negativePercent = round(($negative / $total) * 100, 2);

        $neutralPercent = round(($neutral / $total) * 100, 2);

        if ($positive > $negative) {

            $sentiment = "Positive";

        } elseif ($negative > $positive) {

            $sentiment = "Negative";

        } else {

            $sentiment = "Neutral";

        }

        return [

            'positive' => $positive,
            'negative' => $negative,
            'neutral' => $neutral,

            'positive_percent' => $positivePercent,
            'negative_percent' => $negativePercent,
            'neutral_percent' => $neutralPercent,

            'sentiment' => $sentiment

        ];
    }
}