<?php
// src/Entity/TravelTimeOutput.php

namespace App\Entity;
use Symfony\Component\Serializer\Annotation\Groups;

class TravelTimeOutput
{
    public function __construct(
        #[Groups(['travel_time:read'])]
        public int $heures,

        #[Groups(['travel_time:read'])]
        public int $minutes
    ) {}
}