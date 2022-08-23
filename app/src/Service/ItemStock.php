<?php

namespace App\Service;


use App\Dto\Stock\Stockable;

class ItemStock
{
    private array $stock = [];

    public function put(Stockable $item)
    {
        $this->stock[$item->getKey()] = $this->available($item) + $item->getQty();
    }

    public function take(Stockable $item)
    {
        $available = $this->available($item);
        $take = min($available, $item->getQty());
        $this->stock[$item->getKey()] = $available - $take;
    }

    public function available(Stockable $item): int
    {
        return $this->stock[$item->getKey()] ?? 0;
    }
}
