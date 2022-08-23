<?php

namespace App\Controller\Campervan;

use App\Dto\Response\Mapper\CampervanDtoMapper;
use App\Repository\CampervanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    #[Route('/campervans', name: 'app_campervan_list')]
    public function __invoke(CampervanRepository $repository, CampervanDtoMapper $mapper): JsonResponse
    {
        return $this->json([
            'data' => $mapper->transformFromObjects($repository->findAll())
        ]);
    }
}
