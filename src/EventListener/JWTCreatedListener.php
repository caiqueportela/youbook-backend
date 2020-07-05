<?php

namespace App\EventListener;

use App\Entity\User;
use App\Security\ApiVoter;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener
{

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     * Ao criar o token (login) podemos adicionar informações a serem colocadas no token
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();

        /** @var User $user */
        $user = $event->getUser();

        $payload = $event->getData();
        $payload['ip'] = $request->getClientIp();
        $payload['email'] = $user->getEmail();
        $payload['userId'] = $user->getUserId();
        $payload['isAdmin'] = in_array(ApiVoter::ADMIN_ROLE, $user->getRoles());
        $payload['isAuthor'] = in_array(ApiVoter::AUTHOR_ROLE, $user->getRoles());

//        $expiration = new \DateTime('+1 day');
//        $expiration->setTime(2, 0, 0);
//        $payload['exp'] = $expiration->getTimestamp();

        $event->setData($payload);
    }

}