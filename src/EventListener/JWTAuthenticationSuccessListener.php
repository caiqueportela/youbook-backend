<?php

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTAuthenticationSuccessListener
{

    /**
     * @param AuthenticationSuccessEvent $event
     * Adiciona dados ao objeto de resposta ao se autenticar
     */
    public function onJWTAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        /** @var User $user */
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

//        $data['data'] = array(
//            'roles' => $user->getRoles(),
//        );

        $event->setData($data);
    }

}