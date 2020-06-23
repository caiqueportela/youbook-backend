<?php

namespace App\Controller;

use App\Security\ApiVoter;
use App\Service\PostCommentService;
use App\Validator\Exception\PostNotFound;
use App\Validator\Exception\UserIsNotCommentOwner;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class PostCommentController extends ApiController
{

    /** @var PostCommentService */
    private $postCommentService;

    /** @var TranslatorInterface */
    private $translator;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        PostCommentService $postCommentService,
        TranslatorInterface $translator,
        SerializerInterface $serializer
    ) {
        $this->postCommentService = $postCommentService;
        $this->translator = $translator;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/post/{postId}/comment", name="Create comment in post", methods={"POST", "OPTIONS"})
     */
    public function createPostComment($postId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $bodyData = json_decode($request->getContent(), true);

            $this->postCommentService->createComment($postId, $bodyData);

            return $this->respondCreated($this->translator->trans('api.comment.create.success'));
        } catch(PostNotFound $exception) {
            return $this->setStatusCode($exception->getCode())->respondWithErrors($this->translator->trans('api.post.get.not_found'));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/post/{postId}/comments", name="List comments of post", methods={"GET", "OPTIONS"})
     */
    public function listComments($postId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $comments = $this->postCommentService->listComments($postId, $request->query->getInt('page', 1));

            $serializedComments = $this->serializer->serialize(
                $comments,
                'json'
            );

            return $this->respondSuccessWithData($serializedComments);
        } catch(PostNotFound $exception) {
            return $this->setStatusCode($exception->getCode())->respondWithErrors($this->translator->trans('api.post.get.not_found'));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/post/{postId}/comment/{commentId}", name="Get comment", methods={"GET", "OPTIONS"})
     */
    public function getComment($postId, $commentId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $comment = $this->postCommentService->getComment($postId, $commentId);

            if (is_null($comment)) {
                return $this->respondNotFound($this->translator->trans('api.comment.get.not_found'));
            }

            $serializedComment = $this->serializer->serialize(
                $comment,
                'json'
            );

            return $this->respondSuccessWithData($serializedComment);
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/post/{postId}/comment/{commentId}", name="Delete comment", methods={"DELETE", "OPTIONS"})
     */
    public function deleteComment($postId, $commentId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $comment = $this->postCommentService->getComment($postId, $commentId);

            if (is_null($comment)) {
                return $this->respondNotFound($this->translator->trans('api.comment.get.not_found'));
            }

            $this->postCommentService->deleteComment($comment);

            return $this->respondWithSuccess($this->translator->trans('api.comment.delete.success'));
        } catch(UserIsNotCommentOwner $exception) {
            return $this->setStatusCode($exception->getCode())->respondWithErrors($this->translator->trans("api.comment.delete.user_not_owner"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/post/{postId}/comment/{commentId}", name="Update a comment", methods={"PATCH", "OPTIONS"})
     */
    public function updateComment($postId, $commentId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $comment = $this->postCommentService->getComment($postId, $commentId);

            if (is_null($comment)) {
                return $this->respondNotFound($this->translator->trans('api.comment.get.not_found'));
            }

            $bodyData = json_decode($request->getContent(), true);

            $this->postCommentService->updateComment($comment, $bodyData);

            return $this->respondWithSuccess($this->translator->trans('api.comment.updated.success'));
        } catch(UserIsNotCommentOwner $exception) {
            return $this->setStatusCode($exception->getCode())->respondWithErrors($this->translator->trans("api.comment.delete.user_not_owner"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

}