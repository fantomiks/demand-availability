<?php

namespace App\Dto\Response;

use App\Dto\Stock\Stockable;

class DemandItemResponseDto implements Stockable
{
    public int $id;
    public int $qty;
    public int $available = 0;
    public EquipmentResponseDto $equipment;

    public function getKey(): int
    {
        return $this->equipment->id;
    }

    public function getQty(): int
    {
        return $this->qty;
    }
}
