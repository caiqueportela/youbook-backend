<?php

namespace App\Controller;

use App\Security\ApiVoter;
use App\Service\PostService;
use App\Validator\Exception\UserIsNotPostOwner;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class PostController extends ApiController
{

    /** @var PostService */
    private $postService;

    /** @var TranslatorInterface */
    private $translator;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        PostService $postService,
        TranslatorInterface $translator,
        SerializerInterface $serializer
    ) {
        $this->postService = $postService;
        $this->translator = $translator;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/post", name="Create post", methods={"POST", "OPTIONS"})
     */
    public function createPost(Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $bodyData = json_decode($request->getContent(), true);

            $this->postService->createPost($bodyData);

            return $this->respondCreated($this->translator->trans('api.post.create.success'));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/posts", name="List posts", methods={"GET", "OPTIONS"})
     */
    public function listPosts(Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $posts = $this->postService->listPosts($request->query->getInt('page', 1));

            $serializedPosts = $this->serializer->serialize(
                $posts,
                'json'
            );

            return $this->respondSuccessWithData($serializedPosts);
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/post/{id}", name="Detail post", methods={"GET", "OPTIONS"})
     */
    public function getPost($id, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $post = $this->postService->getPost($id);

            if (is_null($post)) {
                return $this->respondNotFound($this->translator->trans('api.post.get.not_found'));
            }

            $serializedPost = $this->serializer->serialize(
                $post,
                'json'
            );

            return $this->respondSuccessWithData($serializedPost);
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/post/{id}", name="Delete post", methods={"DELETE", "OPTIONS"})
     */
    public function deletePost($id, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $post = $this->postService->getPost($id);

            if (is_null($post)) {
                return $this->respondNotFound($this->translator->trans('api.post.get.not_found'));
            }

            $this->postService->deletePost($post);

            return $this->respondWithSuccess($this->translator->trans('api.post.delete.success'));
        } catch(UserIsNotPostOwner $exception) {
            return $this->setStatusCode($exception->getCode())->respondWithErrors($this->translator->trans("api.post.delete.user_not_owner"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/post/{id}", name="Update post", methods={"PATCH", "OPTIONS"})
     */
    public function updatePost($id, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $post = $this->postService->getPost($id);

            if (is_null($post)) {
                return $this->respondNotFound($this->translator->trans('api.post.get.not_found'));
            }

            $bodyData = json_decode($request->getContent(), true);

            $this->postService->updatePost($post, $bodyData);

            return $this->respondWithSuccess($this->translator->trans('api.post.updated.success'));
        } catch(UserIsNotPostOwner $exception) {
            return $this->setStatusCode($exception->getCode())->respondWithErrors($this->translator->trans("api.post.delete.user_not_owner"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

}
