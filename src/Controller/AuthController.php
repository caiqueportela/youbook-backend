<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\GroupRole;
use App\Entity\GroupUser;
use App\Entity\User;
use App\Entity\UserRole;
use App\Security\ApiVoter;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthController extends ApiController
{

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
            $em = $this->getDoctrine()->getManager();
            $request = $this->transformJsonBody($request);
            $username = $request->get('username');
            $password = $request->get('password');
            $email = $request->get('email');

            if (empty($username) || empty($password) || empty($email)){
                return $this->respondValidationError("Invalid Username or Password or Email");
            }

            $user = new User();
            $user->setPassword($encoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setUsername($username);

            $roleUser = $em->getRepository(UserRole::class)->findOneByName(ApiVoter::USER_ROLE);
            $user->addRole($roleUser);

            $group = new Group();
            $group->setName($username);

            $groupUser = new GroupUser();
            $groupUser->setGroup($group);
            $groupUser->setUser($user);

            $roleGroupAdmin = $em->getRepository(GroupRole::class)->findOneByName(ApiVoter::GROUP_ADMIN_ROLE);
            $groupUser->addRole($roleGroupAdmin);

            $user->addGroupUser($groupUser);

            $em->persist($user);
            $em->flush();

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