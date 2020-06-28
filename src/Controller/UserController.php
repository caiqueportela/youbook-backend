<?php

namespace App\Controller;

use App\Security\ApiVoter;
use App\Service\UserService;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends ApiController
{

    /** @var UserService */
    private $userService;

    /** @var TranslatorInterface */
    private $translator;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        UserService $userService,
        TranslatorInterface $translator,
        SerializerInterface $serializer
    ) {
        $this->userService = $userService;
        $this->translator = $translator;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/exists/{username}", name="Check if user exists", methods={"GET", "OPTIONS"})
     */
    public function usernameInUse($username, Request $request)
    {
        try {
            $userIsTaken = $this->userService->userIsTaken($username);

            return $this->response($userIsTaken);
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

}