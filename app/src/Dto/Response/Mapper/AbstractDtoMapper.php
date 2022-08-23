<?php

namespace App\Dto\Response\Mapper;

abstract class AbstractDtoMapper implements DtoMapperInterface
{
    public function transformFromObjects(iterable $objects): iterable
    {
        $dto = [];

        foreach ($objects as $object) {
            $dto[] = $this->transformFromObject($object);
        }

        return $dto;
    }
}
