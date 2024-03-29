<?php

namespace App\Controller;

use App\Helper\ResponseFactory;
use App\Service\PositionService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PositionController
{
    /**
     * @var PositionService
     */
    private PositionService $service;

    public function __construct(PositionService $service)
    {
        $this->service = $service;
    }

    public function getAll(): JsonResponse
    {
        $success = false;
        $return = null;

        try {
            $return = $this->service->getAll();

            $success = true;
            $status = Response::HTTP_OK;
        } catch (Exception $e) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;

            if (getenv('APP_ENV') !== 'Prod') {
                $return = empty($e->getMessage()) ? null : $e->getMessage();
            }
        }

        $responseFactory = new ResponseFactory(
            $success,
            $return,
            $status
        );

        return $responseFactory->getResponse();
    }
}