<?php

namespace App\Controller;

use App\Helper\DTOFactoryInterface;
use App\Helper\ResponseFactory;
use App\Helper\UserDTOFactory;
use App\Helper\ValidationsErrorFactory;
use App\Service\UserService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController
{
    private UserService $service;
    private DTOFactoryInterface $DTOFactory;

    public function __construct(
        UserService $userService,
        UserDTOFactory $userDTOFactory
    )
    {
        $this->service = $userService;
        $this->DTOFactory = $userDTOFactory;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function login(Request $request): Response
    {
        $success = false;
        $return = null;

        try {
            $userDTO = $this->DTOFactory->makeDTO($request->getContent());
            $logged = $this->service->login($userDTO);

            if ($logged) {
                $success = true;
                $status = Response::HTTP_OK;
                $return = [
                    'access_token' => $this->service->getToken()
                ];
            } else {
                $validationErrors = $this->service->getValidationErrors();
                $messages = (new ValidationsErrorFactory($validationErrors))->getMessages();

                if ($messages) {
                    $status = Response::HTTP_BAD_REQUEST;
                    $return = $messages;
                } else {
                    $status = Response::HTTP_UNAUTHORIZED;
                }
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
