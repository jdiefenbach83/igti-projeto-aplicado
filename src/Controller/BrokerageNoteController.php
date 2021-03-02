<?php

namespace App\Controller;

use App\Helper\BrokerageNoteDTOFactory;
use App\Helper\OperationDTOFactory;
use App\Helper\ResponseFactory;
use App\Helper\ValidationsErrorFactory;
use App\Service\BrokerageNoteService;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Exception\ValidatorException;

class BrokerageNoteController extends BaseController
{
    /**
     * @var OperationDTOFactory
     */
    private OperationDTOFactory $operationDTOFactory;

    public function __construct(
        BrokerageNoteService $brokerageNoteService,
        BrokerageNoteDTOFactory $brokerageNoteDTOFactory,
        OperationDTOFactory $operationDTOFactory
    )
    {
        parent::__construct($brokerageNoteService, $brokerageNoteDTOFactory);

        $this->operationDTOFactory = $operationDTOFactory;
    }

    public function addOperation(int $id, Request $request): Response
    {
        $success = false;
        $return = null;

        try {
            $new_dto = $this->operationDTOFactory->makeDTO($request->getContent());
            $saved = $this->service->addOperation($id, $new_dto);

            if ($saved) {
                $success = true;
                $status = Response::HTTP_CREATED;
                $return = $saved;

            } else {
                $status = Response::HTTP_BAD_REQUEST;
                $validation_errors = $this->service->getValidationErrors();
                $return = (new ValidationsErrorFactory($validation_errors))->getMessages();
            }

        } catch (ValidatorException $e) {
            $status = Response::HTTP_BAD_REQUEST;
            $return = $e->getMessage();

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

    public function updateOperation(int $id, int $line, Request $request): Response
    {
        $success = false;
        $return = null;

        try {
            $dto_to_update = $this->operationDTOFactory->makeDTO($request->getContent());
            $saved = $this->service->updateOperation($id, $line, $dto_to_update);

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

        } catch (ValidatorException $e) {
            $status = Response::HTTP_BAD_REQUEST;
            $return = $e->getMessage();

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

    public function removeOperation(int $id, int $line): Response
    {
        $success = false;
        $return = null;

        try {
            $this->service->removeOperation($id, $line);

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
