<?php

namespace App\Controller;

use App\Helper\DTOFactoryInterface;
use App\Helper\ResponseFactory;
use App\Helper\ValidationsErrorFactory;
use App\Service\ServiceInterface;
use Exception;
use InvalidArgumentException;
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
     * @var DTOFactoryInterface
     */
    private DTOFactoryInterface $DTOFactory;

    public function __construct(ServiceInterface $service, DTOFactoryInterface $DTOFactory)
    {
        $this->service = $service;
        $this->DTOFactory = $DTOFactory;
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

    public function getById(int $id): JsonResponse
    {
        $success = false;
        $return = null;

        try {
            $entity = $this->service->getById($id);

            $success = true;
            $status = is_null($entity) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
            $return = $entity;
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

    public function add(Request $request): Response
    {
       $success = false;
       $return = null;

       try {
            $new_dto = $this->DTOFactory->makeDTO($request->getContent());
            $saved = $this->service->add($new_dto);

            if ($saved) {
                $success = true;
                $status = Response::HTTP_CREATED;
                $return = $saved;

            } else {
                $status = Response::HTTP_BAD_REQUEST;
                $validation_errors = $this->service->getValidationErrors();
                $return = (new ValidationsErrorFactory($validation_errors))->getMessages();
            }

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

    public function update(int $id, Request $request): Response
    {
        $success = false;
        $return = null;

        try {
            $dto_to_update = $this->DTOFactory->makeDTO($request->getContent());
            $saved = $this->service->update($id, $dto_to_update);

            if ($saved) {
                $success = true;
                $status = Response::HTTP_OK;
                $return = $saved;

            } else {
                $status = Response::HTTP_BAD_REQUEST;
                $validation_errors = $this->service->getValidationErrors();
                $return = (new ValidationsErrorFactory($validation_errors))->getMessages();
            }

        } catch (InvalidArgumentException $e) {
            $status = Response::HTTP_NOT_FOUND;

            if (getenv('APP_ENV') !== 'Prod') {
                $return = empty($e->getMessage()) ? null : $e->getMessage();
            }

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

    public function remove(int $id): Response
    {
        $success = false;
        $return = null;

        try {
            $this->service->remove($id);

            $success = true;
            $status = Response::HTTP_NO_CONTENT;
        } catch (InvalidArgumentException $e) {
            $status = Response::HTTP_NOT_FOUND;

            if (getenv('APP_ENV') !== 'Prod') {
                $return = empty($e->getMessage()) ? null : $e->getMessage();
            }

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