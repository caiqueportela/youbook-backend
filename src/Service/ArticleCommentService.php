<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\ArticleComment;
use App\Entity\User;
use App\Repository\ArticleCommentRepository;
use App\Repository\ArticleRepository;
use App\Validator\Exception\ArticleNotFound;
use App\Validator\Exception\UserIsNotCommentOwner;
use Symfony\Component\Security\Core\Security;

class ArticleCommentService
{

    /** @var User */
    private $user;

    /** @var ArticleRepository */
    private $articleRepository;

    /** @var ArticleCommentRepository */
    private $articleCommentRepository;

    /** @var YoubookPaginator */
    private $paginator;

    public function __construct(
        Security $security,
        ArticleRepository $articleRepository,
        ArticleCommentRepository $articleCommentRepository,
        YoubookPaginator $paginator
    ) {
        $this->user = $security->getUser();
        $this->articleRepository = $articleRepository;
        $this->articleCommentRepository = $articleCommentRepository;
        $this->paginator = $paginator;
    }

    public function createComment($articleId, $data)
    {
        $article = $this->findArticleById($articleId);

        $comment = new ArticleComment();
        $comment->setMessage($data['message']);
        $comment->setOwner($this->user);
        $comment->setArticle($article);
        $this->articleCommentRepository->persistComment($comment);
    }

    public function listComments($articleId, int $page)
    {
        /** @var Article $article */
        $article = $this->findArticleById($articleId);

        $comments = $this->articleCommentRepository->findArticleCommentsToPagination($article->getArticleId());

        $this->paginator->setCurrentPage($page);
        $this->paginator->setQuery($comments);

        return $this->paginator->paginate();
    }

    public function getComment($commentId)
    {
        return $this->articleCommentRepository->findComment($commentId);
    }

    public function deleteComment(ArticleComment $comment)
    {
        $this->validateCommentOwner($comment);

        $comment->setUpdatedAt(new \DateTime());
        $comment->setDeleted(true);
        $this->articleCommentRepository->persistComment($comment);
    }

    public function updateComment(ArticleComment $comment, $data)
    {
        $this->validateCommentOwner($comment);

        $comment->setMessage($data['message']);
        $comment->setUpdatedAt(new \DateTime());
        $this->articleCommentRepository->persistComment($comment);
    }

    private function findArticleById($articleId)
    {
        $article = $this->articleRepository->findArticle($articleId);

        if (is_null($article)) {
            throw new ArticleNotFound();
        }

        return $article;
    }

    private function validateCommentOwner(ArticleComment $comment)
    {
        if ($comment->getOwner()->getUserId() !== $this->user->getUserId()) {
            throw new UserIsNotCommentOwner();
        }
    }

}