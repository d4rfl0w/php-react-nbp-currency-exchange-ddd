<?php

declare(strict_types=1);

namespace App\UI\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{

    public function index(): Response
    {
        return $this->render('exchange_rates/app-root.html.twig');
    }

    public function setupCheck(Request $request): Response
    {
        $responseContent = json_encode([
            'testParam' => $request->get('testParam') ? (int) $request->get('testParam') : null
        ]);

        return new Response(
            $responseContent,
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }
}
