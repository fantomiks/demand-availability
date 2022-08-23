<?php

namespace App\Controller\Demand;

use App\Service\DemandAvailabilityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    private DemandAvailabilityService $service;

    private const DATE_FORMAT = 'Y-m-d';

    public function __construct(DemandAvailabilityService $service)
    {
        $this->service = $service;
    }

    #[Route('/demands/{stationId}', name: 'app_demand_list')]
    public function __invoke(int $stationId, Request $request): JsonResponse
    {
        $start = $request->get('start', date(self::DATE_FORMAT));
        $startDate = \DateTime::createFromFormat(self::DATE_FORMAT, $start);

        return $this->json([
            'data' => $this->service->calculateAvailableItems($stationId, $startDate),
        ]);
    }
}
