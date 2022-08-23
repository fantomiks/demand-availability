<?php

namespace App\Controller\Station;

use App\Dto\Response\Mapper\StationDtoMapper;
use App\Repository\StationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    #[Route('/stations', name: 'app_station_list')]
    public function __invoke(StationRepository $repository, StationDtoMapper $mapper): JsonResponse
    {
        return $this->json([
            'data' => $mapper->transformFromObjects($repository->findAll())
        ]);
    }
}
