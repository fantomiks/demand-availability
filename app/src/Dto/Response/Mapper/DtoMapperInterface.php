<?php

namespace App\Dto\Response\Mapper;

interface DtoMapperInterface
{
    public function transformFromObject($object);
    public function transformFromObjects(iterable $objects): iterable;
}
