<?php

namespace App\Dto\Response\Mapper;


use App\Dto\Response\DemandItemResponseDto;
use App\Entity\DemandItem;

class DemandItemDtoMapper extends AbstractDtoMapper
{
    private EquipmentDtoMapper $equipmentResponseDtoMapper;
    private int $qty;
//    public DemandDtoMapper $demandResponseDtoMapper;

    public function __construct(
        EquipmentDtoMapper $equipmentResponseDtoMapper,
//        DemandDtoMapper    $demandResponseDtoMapper
    ) {
        $this->equipmentResponseDtoMapper = $equipmentResponseDtoMapper;
//        $this->demandResponseDtoMapper = $demandResponseDtoMapper;
    }

    /**
     * @param DemandItem $demandItem
     *
     * @return DemandItemResponseDto
     */
    public function transformFromObject($demandItem): DemandItemResponseDto
    {
        $dto = new DemandItemResponseDto();
        $dto->id = $demandItem->getId();
        $dto->qty = $demandItem->getQty();
//        $dto->demand = $this->demandResponseDtoMapper->transformFromObject($demandItem->getDemand());
        $dto->equipment = $this->equipmentResponseDtoMapper->transformFromObject($demandItem->getEquipment());

        return $dto;
    }
}
