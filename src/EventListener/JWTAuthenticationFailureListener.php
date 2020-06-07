<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

class JWTAuthenticationFailureListener
{

    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param AuthenticationFailureEvent $event
     */
    public function onJWTAuthenticationFailure(AuthenticationFailureEvent $event)
    {
        $response = new JWTAuthenticationFailureResponse($this->translator->trans('jwt.authentication.failure'));

        $event->setResponse($response);
    }

}