<?php

namespace App\Service ;

class NotificationService
{
    public function generate(int $min, int $max): int
    {
        return random_int($min, $max);
    }
}
