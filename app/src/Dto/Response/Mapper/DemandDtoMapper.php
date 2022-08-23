<?php

namespace App\Dto\Response\Mapper;


use App\Dto\Response\DemandResponseDto;
use App\Entity\Demand;

class DemandDtoMapper extends AbstractDtoMapper
{
    private CustomerDtoMapper $customerResponseDtoMapper;
    private StationDtoMapper $stationResponseDtoMapper;
    private CampervanDtoMapper $campervanResponseDtoMapper;
    private DemandItemDtoMapper $demandItemDtoMapper;

    public function __construct(
        CustomerDtoMapper  $customerResponseDtoMapper,
        StationDtoMapper   $stationResponseDtoMapper,
        CampervanDtoMapper $campervanResponseDtoMapper,
        DemandItemDtoMapper $demandItemDtoMapper
    ) {
        $this->customerResponseDtoMapper = $customerResponseDtoMapper;
        $this->stationResponseDtoMapper = $stationResponseDtoMapper;
        $this->campervanResponseDtoMapper = $campervanResponseDtoMapper;
        $this->demandItemDtoMapper = $demandItemDtoMapper;
    }

    /**
     * @param Demand $demand
     *
     * @return DemandResponseDto
     */
    public function transformFromObject($demand): DemandResponseDto
    {
        $dto = new DemandResponseDto();
        $dto->id = $demand->getId();
        $dto->customer = $this->customerResponseDtoMapper->transformFromObject($demand->getCustomer());
        $dto->startStation = $this->stationResponseDtoMapper->transformFromObject($demand->getStartStation());
        $dto->endStation = $this->stationResponseDtoMapper->transformFromObject($demand->getEndStation());
        $dto->campervan = $this->campervanResponseDtoMapper->transformFromObject($demand->getCampervan());
        $dto->rentalStartDate = $demand->getRentalStart();
        $dto->rentalEndDate = $demand->getRentalEnd();
        $dto->demandItems = $this->demandItemDtoMapper->transformFromObjects($demand->getDemandItems());

        return $dto;
    }
}
