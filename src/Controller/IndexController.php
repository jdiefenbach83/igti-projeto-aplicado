<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class IndexController extends AbstractController
{
    const ALLOWED_ROUTES_PREFIX = [
        '/',
        'brokers',
        'brokerageNotes',
    ];

    public function index(Request $request): Response
    {
        $pathInfo = $request->getPathInfo();

        if ($this->shouldRenderFrontEnd($pathInfo)){
            return $this->render('base.html.twig', []);
        }

        throw $this->createNotFoundException();
    }

    private function shouldRenderFrontEnd(string $pathInfo): bool
    {
        foreach (self::ALLOWED_ROUTES_PREFIX as $route) {
            if (str_contains($pathInfo, $route)) {
                return true;
            }
        }

        return false;
    }
}