<?php

namespace App\Security;

use App\Service\UserService;
use Exception;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JwtAuthenticator extends AbstractGuardAuthenticator
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // TODO: Implement start() method.
    }

    public function supports(Request $request): bool
    {
        return $this->isProtectedURI($request->getPathInfo());
    }

    private function isProtectedURI(string $uri): bool
    {
        $protectedUris = [
            '/api/companies',
            '/api/assets',
            '/api/brokers',
            '/api/brokerageNotes',
            '/api/positions',
            '/api/consolidations',
            '/api/goods',
        ];

        foreach ($protectedUris as $protectedUri) {
            if (strpos($uri, $protectedUri) !== false) {
                return true;
            }
        }

        return false;
    }

    public function getCredentials(Request $request)
    {
        $token = str_replace(
            'Bearer ',
            '',
            $request->headers->get('authorization')
        );

        try {
            return JWT::decode($token, 'key', ['HS256']);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!is_object($credentials) || !property_exists($credentials, 'email')) {
            return null;
        }

        $email = $credentials->email;

        return $this->userService->findByEmail($email);
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return is_object($credentials) && property_exists($credentials, 'email');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        return new JsonResponse([
            'erro' => 'Falha na autenticação'
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return null;
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}