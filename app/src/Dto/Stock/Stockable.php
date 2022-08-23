<?php

namespace App\Dto\Stock;

interface Stockable
{
    public function getKey(): int;
    public function getQty(): int;
}
