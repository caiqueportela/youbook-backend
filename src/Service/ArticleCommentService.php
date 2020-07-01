<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Validator\Exception\ArticleNotFound;
use App\Validator\Exception\UserIsNotCommentOwner;
use Symfony\Component\Security\Core\Security;

class ArticleCommentService
{

    /** @var User */
    private $user;

    /** @var ArticleRepository */
    private $articleRepository;

    /** @var CommentRepository */
    private $commentRepository;

    /** @var YoubookPaginator */
    private $paginator;

    public function __construct(
        Security $security,
        ArticleRepository $articleRepository,
        CommentRepository $commentRepository,
        YoubookPaginator $paginator
    ) {
        $this->user = $security->getUser();
        $this->articleRepository = $articleRepository;
        $this->commentRepository = $commentRepository;
        $this->paginator = $paginator;
    }

    public function createComment($articleId, $data)
    {
        $article = $this->findArticleById($articleId);

        $comment = new Comment();
        $comment->setMessage($data['message']);
        $comment->setOwner($this->user);
        $comment->setArticle($article);
        $this->commentRepository->persistComment($comment);

        return $comment;
    }

    public function listComments($articleId, int $page)
    {
        /** @var Article $article */
        $article = $this->findArticleById($articleId);

        $comments = $this->commentRepository->findArticleCommentsToPagination($article->getArticleId());

        $this->paginator->setCurrentPage($page);
        $this->paginator->setQuery($comments);

        return $this->paginator->paginate();
    }

    public function getComment($articleId, $commentId)
    {
        return $this->commentRepository->findArticleComment($articleId, $commentId);
    }

    public function deleteComment(Comment $comment)
    {
        $this->validateCommentOwner($comment);

        $comment->setUpdatedAt(new \DateTime());
        $comment->setDeleted(true);
        $this->commentRepository->persistComment($comment);
    }

    public function updateComment(Comment $comment, $data)
    {
        $this->validateCommentOwner($comment);

        $comment->setMessage($data['message']);
        $comment->setUpdatedAt(new \DateTime());
        $this->commentRepository->persistComment($comment);
    }

    private function findArticleById($articleId)
    {
        $article = $this->articleRepository->findArticle($articleId);

        if (is_null($article)) {
            throw new ArticleNotFound();
        }

        return $article;
    }

    private function validateCommentOwner(Comment $comment)
    {
        if ($comment->getOwner()->getUserId() !== $this->user->getUserId()) {
            throw new UserIsNotCommentOwner();
        }
    }

}