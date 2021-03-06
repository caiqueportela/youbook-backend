<?php

namespace App\Controller;

use App\Service\UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthController extends ApiController
{

    /** @var UserService */
    private $userService;

    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return JsonResponse
     *
     * @Route("/api/register", name="Create user", methods={"POST", "OPTIONS"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
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

    /**
     * @param UserInterface $user
     * @param JWTTokenManagerInterface $JWTManager
     * @return JsonResponse
     *
     * @Route("/api/login", name="Login with user", methods={"POST", "OPTIONS"})
     */
    public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $JWTManager)
    {
        try {
            return new JsonResponse([
                'token' => $JWTManager->create($user)
            ]);
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

}