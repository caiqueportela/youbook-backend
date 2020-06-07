<?php

namespace App\EventListener;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTDecodedListener
{

    /**
     * @var RequestStack
     */
    private $requestStack;

    /** @var UserRepository */
    private $userRepository;

    /**
     * @param RequestStack $requestStack
     * @param UserRepository $userRepository
     */
    public function __construct(RequestStack $requestStack, UserRepository $userRepository)
    {
        $this->requestStack = $requestStack;
        $this->userRepository = $userRepository;
    }

    /**
     * @param JWTDecodedEvent $event
     * Intercepta a tentativa de deocdificação de um token e pode adicionar validações
     */
    public function onJWTDecoded(JWTDecodedEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = $event->getPayload();

        /** @var User $user */
        $user = $this->userRepository->loadUserByUsername($payload['username']);

        if (!$user) {
            $event->markAsInvalid();
        }

        $request->setLocale($user->getLocale());
    }

}