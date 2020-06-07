<?php

namespace App\Controller;

use App\Security\ApiVoter;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends ApiController
{

    /**
     * @Route("/api/post", name="List posts", methods={"GET", "OPTIONS"})
     */
    public function index()
    {
        $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PostController.php',
        ]);
    }

}
