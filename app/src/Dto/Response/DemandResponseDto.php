<?php

namespace App\Dto\Response;

use App\Dto\Stock\Stockable;

class DemandResponseDto implements Stockable
{
    public int $id;
    public int $available = 0;
    public CustomerResponseDto $customer;
    public StationResponseDto $startStation;
    public StationResponseDto $endStation;
    public CampervanResponseDto $campervan;
    public \DateTimeInterface $rentalStartDate;
    public \DateTimeInterface $rentalEndDate;
    /** @var array|DemandItemResponseDto[] */
    public iterable $demandItems;

    public function getKey(): int
    {
        return $this->campervan->id;
    }

    public function getQty(): int
    {
        return 1;
    }
}
