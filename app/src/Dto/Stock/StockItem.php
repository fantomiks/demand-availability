<?php

namespace App\Dto\Stock;

class StockItem implements Stockable
{
    private int $key;
    private int $qty;

    public function __construct(int $key, int $qty)
    {
        $this->key = $key;
        $this->qty = $qty;
    }

    public function getKey(): int
    {
        return $this->key;
    }

    public function getQty(): int
    {
        return $this->qty;
    }
}
