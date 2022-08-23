<?php

namespace App\Dto\Response\Mapper;

use App\Dto\Response\CampervanResponseDto;
use App\Entity\Campervan;

class CampervanDtoMapper extends AbstractDtoMapper
{
    /**
     * @param Campervan $campervan
     *
     * @return CampervanResponseDto
     */
    public function transformFromObject($campervan): CampervanResponseDto
    {
        $dto = new CampervanResponseDto();
        $dto->id = $campervan->getId();
        $dto->name = $campervan->getName();

        return $dto;
    }
}
