<?php

namespace App\Controller;

use App\Security\ApiVoter;
use App\Service\ArticleService;
use App\Validator\Exception\SubjectNotFound;
use App\Validator\Exception\UserIsNotArticleOwner;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ArticleController extends ApiController
{

    /** @var ArticleService */
    private $articleService;

    /** @var TranslatorInterface */
    private $translator;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        ArticleService $articleService,
        TranslatorInterface $translator,
        SerializerInterface $serializer
    ) {
        $this->articleService = $articleService;
        $this->translator = $translator;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/article", name="Create article", methods={"POST", "OPTIONS"})
     */
    public function createArticle(Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $bodyData = json_decode($request->getContent(), true);

            $this->articleService->createArticle($bodyData);

            return $this->respondCreated($this->translator->trans('api.article.create.success'));
        } catch(SubjectNotFound $exception) {
            return $this->setStatusCode($exception->getCode())->respondWithErrors($this->translator->trans("api.subject.get.not_found"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/articles", name="List articles", methods={"GET", "OPTIONS"})
     */
    public function listArticles(Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $articles = $this->articleService->listArticles($request->query->getInt('page', 1));

            $serializedArticles = $this->serializer->serialize(
                $articles,
                'json'
            );

            return $this->respondSuccessWithData($serializedArticles);
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/article/{articleId}", name="Detail article", methods={"GET", "OPTIONS"})
     */
    public function getArticle($articleId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $article = $this->articleService->getArticle($articleId);

            if (is_null($article)) {
                return $this->respondNotFound($this->translator->trans('api.article.get.not_found'));
            }

            $serializedArticle = $this->serializer->serialize(
                $article,
                'json'
            );

            return $this->respondSuccessWithData($serializedArticle);
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/article/{articleId}", name="Delete article", methods={"DELETE", "OPTIONS"})
     */
    public function deleteArticle($articleId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $article = $this->articleService->getArticle($articleId);

            if (is_null($article)) {
                return $this->respondNotFound($this->translator->trans('api.article.get.not_found'));
            }

            $this->articleService->deleteArticle($article);

            return $this->respondWithSuccess($this->translator->trans('api.article.delete.success'));
        } catch(UserIsNotArticleOwner $exception) {
            return $this->setStatusCode($exception->getCode())->respondWithErrors($this->translator->trans("api.article.delete.user_not_owner"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/article/{articleId}", name="Update article", methods={"PATCH", "OPTIONS"})
     */
    public function updateArticle($articleId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $article = $this->articleService->getArticle($articleId);

            if (is_null($article)) {
                return $this->respondNotFound($this->translator->trans('api.article.get.not_found'));
            }

            $bodyData = json_decode($request->getContent(), true);

            $this->articleService->updateArticle($article, $bodyData);

            return $this->respondWithSuccess($this->translator->trans('api.article.updated.success'));
        } catch(UserIsNotArticleOwner $exception) {
            return $this->setStatusCode($exception->getCode())->respondWithErrors($this->translator->trans("api.article.delete.user_not_owner"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

}