<?php

namespace App\EventListener;

use App\Entity\Usuario;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSuccessListener
{
    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
        private RequestStack $requestStack,
    ) {}

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        $request = $this->requestStack->getSession();

        if (!$user instanceof Usuario) {
            return;
        }

        $token = $this->jwtManager->create($user);
        $request->set('jwt_token', $token);
    }
}
