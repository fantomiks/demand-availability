<?php

namespace App\Dto\Response;

class EventResponseDto
{
    public string $id;
    public string $title;
    public string $start;
//    public string $end;
    public DemandResponseDto $demand;
}
