<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Application\Service\ExchangeRateService;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use DateTime;


class ExchangeRatesController extends AbstractController
{
    private $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }

    /**
     * @param string $date Date in Y-m-d format.
     */
    public function getExchangeRates(string $date): JsonResponse
    {
        try {
            $dateObj = new DateTime($date);
            $exchangeRates = $this->exchangeRateService->getExchangeRates($dateObj);
            return new JsonResponse($exchangeRates);
        } catch (InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        } catch (RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
