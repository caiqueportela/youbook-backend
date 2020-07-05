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

    /**
     * @Route("/api/users", name="List users", methods={"GET", "OPTIONS"})
     */
    public function listUsers(Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::ADMIN_ROLE);

            $users = $this->userService->listUsers($request->query->getInt('page', 1));

            $serializedUsers = $this->serializer->serialize(
                $users,
                'json'
            );

            return $this->respondSuccessWithData($serializedUsers);
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/user/register", name="Create user by admin", methods={"POST", "OPTIONS"})
     */
    public function adminRegisterUser(Request $request)
    {
        try {
            $request = $this->transformJsonBody($request);
            $username = $request->get('username');
            $password = $request->get('password');
            $email = $request->get('email');

            if (empty($username) || empty($password) || empty($email)){
                return $this->respondValidationError("Invalid Username or Password or Email");
            }

            $role = $request->get('role');

            $user = $this->userService->createUser($username, $password, $email, $role);

            return $this->respondCreated(sprintf('User %s successfully created', $user->getUsername()));
        } catch (\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

}