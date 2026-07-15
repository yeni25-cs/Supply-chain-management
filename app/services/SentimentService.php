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

        foreach($words as $word){

            if(in_array($word,$positiveWords)){
                $positive++;
            }

            if(in_array($word,$negativeWords)){
                $negative++;
            }

        }

        if($positive > $negative){

            $sentiment="Positive";

        }elseif($negative > $positive){

            $sentiment="Negative";

        }else{

            $sentiment="Neutral";

        }

        return [

            'positive'=>$positive,

            'negative'=>$negative,

            'sentiment'=>$sentiment

        ];
    }
}