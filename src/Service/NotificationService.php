<?php

namespace App\Service ;

class RandomNumberGenerator
{
    public function generate(int $min, int $max): int
    {
        return random_int($min, $max);
    }
}
