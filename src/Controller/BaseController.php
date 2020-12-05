<?php

namespace App\Controller;

use App\Helper\EntityFactoryInterface;
use App\Helper\ResponseFactory;
use App\Helper\ValidationsErrorFactory;
use App\Service\ServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    /**
     * @var ServiceInterface
     */
    private ServiceInterface $service;
    /**
     * @var EntityFactoryInterface
     */
    private EntityFactoryInterface $entityFactory;

    public function __construct(ServiceInterface $service, EntityFactoryInterface $entityFactory)
    {
        $this->service = $service;
        $this->entityFactory = $entityFactory;
    }

    public function getAll(): JsonResponse
    {
       $entityList = $this->service->getAll();

        $responseFactory = new ResponseFactory(
            true,
            $entityList,
            Response::HTTP_OK
        );

        return $responseFactory->getResponse();
    }

    public function getById(int $id): JsonResponse
    {
        $entity = $this->service->getById($id);
        $status = is_null($entity) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        $responseFactory = new ResponseFactory(
            true,
            $entity,
            $status
        );

        return $responseFactory->getResponse();
    }

    public function add(Request $request): Response
    {
       $success = false;

       try {
            $new_entity = $this->entityFactory->makeEntity($request->getContent());
            $saved = $this->service->add($new_entity);

            if ($saved) {
                $success = true;
                $status = Response::HTTP_CREATED;
                $return = $new_entity;

            } else {
                $status = Response::HTTP_BAD_REQUEST;
                $validation_errors = $this->service->getValidationErrors();
                $return = (new ValidationsErrorFactory($validation_errors))->getMessages();
            }

        } catch (\Exception $e) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $return = null;
        }

        $responseFactory = new ResponseFactory(
            $success,
            $return,
            $status
        );

        return $responseFactory->getResponse();
    }

    public function update(int $id, Request $request): Response
    {
        $success = false;

        try {
            $entity_to_update = $this->entityFactory->makeEntity($request->getContent());
            $saved = $this->service->update($id, $entity_to_update);

            if ($saved) {
                $success = true;
                $status = Response::HTTP_OK;
                $return = $saved;

            } else {
                $status = Response::HTTP_BAD_REQUEST;
                $validation_errors = $this->service->getValidationErrors();
                $return = (new ValidationsErrorFactory($validation_errors))->getMessages();
            }

        } catch (\InvalidArgumentException $e) {
            $status = Response::HTTP_NOT_FOUND;
            $return = null;

        } catch (\Exception $e) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $return = null;
        }

        $responseFactory = new ResponseFactory(
            $success,
            $return,
            $status
        );

        return $responseFactory->getResponse();
    }

    public function remove(int $id): Response
    {
        $success = false;

        try {
            $this->service->remove($id);

            $success = true;
            $status = Response::HTTP_NO_CONTENT;
        } catch (\InvalidArgumentException $e) {
            $status = Response::HTTP_NOT_FOUND;

        } catch (\Exception $e) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $responseFactory = new ResponseFactory(
            $success,
            null,
            $status
        );

        return $responseFactory->getResponse();
    }
}