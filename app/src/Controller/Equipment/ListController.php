<?php

namespace App\Controller\Equipment;

use App\Dto\Response\Mapper\EquipmentDtoMapper;
use App\Repository\EquipmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    #[Route('/equipments', name: 'app_equipment_list')]
    public function __invoke(EquipmentRepository $repository, EquipmentDtoMapper $mapper): JsonResponse
    {
        return $this->json([
            'data' => $mapper->transformFromObjects($repository->findAll())
        ]);
    }
}
