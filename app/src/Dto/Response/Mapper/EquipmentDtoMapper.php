<?php

namespace App\Dto\Response\Mapper;


use App\Dto\Response\EquipmentResponseDto;
use App\Entity\Equipment;

class EquipmentDtoMapper extends AbstractDtoMapper
{
    /**
     * @param Equipment $equipment
     *
     * @return EquipmentResponseDto
     */
    public function transformFromObject($equipment): EquipmentResponseDto
    {
        $dto = new EquipmentResponseDto();
        $dto->id = $equipment->getId();
        $dto->name = $equipment->getName();

        return $dto;
    }
}
