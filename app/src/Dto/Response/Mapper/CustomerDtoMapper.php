<?php

namespace App\Dto\Response\Mapper;

use App\Dto\Response\CustomerResponseDto;
use App\Entity\User;

class CustomerDtoMapper extends AbstractDtoMapper
{
    /**
     * @param User $customer
     *
     * @return CustomerResponseDto
     */
    public function transformFromObject($customer): CustomerResponseDto
    {
        $dto = new CustomerResponseDto();
        $dto->id = $customer->getId();
        $dto->email = $customer->getEmail();

        return $dto;
    }
}
