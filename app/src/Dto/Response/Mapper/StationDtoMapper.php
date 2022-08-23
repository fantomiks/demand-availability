<?php

namespace App\Dto\Response\Mapper;


use App\Dto\Response\StationResponseDto;
use App\Entity\Station;

class StationDtoMapper extends AbstractDtoMapper
{
    /**
     * @param Station $station
     *
     * @return StationResponseDto
     */
    public function transformFromObject($station): StationResponseDto
    {
        $dto = new StationResponseDto();
        $dto->id = $station->getId();
        $dto->name = $station->getName();

        return $dto;
    }
}
