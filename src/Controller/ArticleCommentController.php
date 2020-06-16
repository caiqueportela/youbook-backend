<?php

namespace App\Controller;

use App\Security\ApiVoter;
use App\Service\ArticleCommentService;
use App\Validator\Exception\ArticleNotFound;
use App\Validator\Exception\UserIsNotCommentOwner;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ArticleCommentController extends ApiController
{

    /** @var ArticleCommentService */
    private $articleCommentService;

    /** @var TranslatorInterface */
    private $translator;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        ArticleCommentService $articleCommentService,
        TranslatorInterface $translator,
        SerializerInterface $serializer
    ) {
        $this->articleCommentService = $articleCommentService;
        $this->translator = $translator;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/article/{articleId}/comment", name="Create comment in article", methods={"POST", "OPTIONS"})
     */
    public function createArticleComment($articleId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $bodyData = json_decode($request->getContent(), true);

            $this->articleCommentService->createComment($articleId, $bodyData);

            return $this->respondCreated($this->translator->trans('api.comment.create.success'));
        } catch(ArticleNotFound $exception) {
            return $this->setStatusCode($exception->getCode())->respondWithErrors($this->translator->trans('api.article.get.not_found'));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/article/{articleId}/comments", name="List comments of article", methods={"GET", "OPTIONS"})
     */
    public function listComments($articleId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $comments = $this->articleCommentService->listComments($articleId, $request->query->getInt('page', 1));

            $serializedComments = $this->serializer->serialize(
                $comments,
                'json'
            );

            return $this->respondSuccessWithData($serializedComments);
        } catch(ArticleNotFound $exception) {
            return $this->setStatusCode($exception->getCode())->respondWithErrors($this->translator->trans('api.article.get.not_found'));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/article/comment/{commentId}", name="Get article comment", methods={"GET", "OPTIONS"})
     */
    public function getComment($commentId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $comment = $this->articleCommentService->getComment($commentId);

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
     * @Route("/api/article/comment/{commentId}", name="Delete article comment", methods={"DELETE", "OPTIONS"})
     */
    public function deleteComment($commentId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $comment = $this->articleCommentService->getComment($commentId);

            if (is_null($comment)) {
                return $this->respondNotFound($this->translator->trans('api.comment.get.not_found'));
            }

            $this->articleCommentService->deleteComment($comment);

            return $this->respondWithSuccess($this->translator->trans('api.comment.delete.success'));
        } catch(UserIsNotCommentOwner $exception) {
            return $this->setStatusCode($exception->getCode())->respondWithErrors($this->translator->trans("api.comment.delete.user_not_owner"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/article/comment/{commentId}", name="Update a article comment", methods={"PATCH", "OPTIONS"})
     */
    public function updateComment($commentId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $comment = $this->articleCommentService->getComment($commentId);

            if (is_null($comment)) {
                return $this->respondNotFound($this->translator->trans('api.comment.get.not_found'));
            }

            $bodyData = json_decode($request->getContent(), true);

            $this->articleCommentService->updateComment($comment, $bodyData);

            return $this->respondWithSuccess($this->translator->trans('api.comment.updated.success'));
        } catch(UserIsNotCommentOwner $exception) {
            return $this->setStatusCode($exception->getCode())->respondWithErrors($this->translator->trans("api.comment.delete.user_not_owner"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

}