<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\SubjectRepository;
use App\Validator\Exception\SubjectNotFound;
use App\Validator\Exception\UserIsNotArticleOwner;
use Symfony\Component\Security\Core\Security;

class ArticleService
{

    /** @var User */
    private $user;

    /** @var ArticleRepository */
    private $articleRepository;

    /** @var SubjectRepository */
    private $subjectRepository;

    /** @var YoubookPaginator */
    private $paginator;

    public function __construct(
        Security $security,
        ArticleRepository $articleRepository,
        SubjectRepository $subjectRepository,
        YoubookPaginator $paginator
    ) {
        $this->user = $security->getUser();
        $this->articleRepository = $articleRepository;
        $this->subjectRepository = $subjectRepository;
        $this->paginator = $paginator;
    }

    public function createArticle($data)
    {
        $subject = $this->findSubjectById($data['subject']);

        $article = new Article();
        $article->setOwner($this->user);
        $article->setTitle($data['title']);
        $article->setSubtitle($data['subtitle']);
        $article->setContent($data['content']);
        $article->setSubject($subject);
        $this->articleRepository->persistArticle($article);
    }

    private function findSubjectById($subjectId)
    {
        $subject = $this->subjectRepository->findSubject($subjectId);

        if (is_null($subject)) {
            throw new SubjectNotFound();
        }

        return $subject;
    }

    public function listArticles(int $page)
    {
        $articles = $this->articleRepository->findArticlesToPagination();

        $this->paginator->setCurrentPage($page);
        $this->paginator->setQuery($articles);

        return $this->paginator->paginate();
    }

    public function getArticle($articleId)
    {
        return $this->articleRepository->findArticle($articleId);
    }

    public function deleteArticle(Article $article)
    {
        $this->validateArticleOwner($article);

        $this->articleRepository->deleteArticle($article);
    }

    public function updateArticle(Article $article, $data)
    {
        $this->validateArticleOwner($article);

        $subject = $this->findSubjectById($data['subject']);

        $article->setTitle($data['title']);
        $article->setSubtitle($data['subtitle']);
        $article->setContent($data['content']);
        $article->setSubject($subject);
        $article->setUpdatedAt(new \DateTime());
        $this->articleRepository->persistArticle($article);
    }

    private function validateArticleOwner(Article $article)
    {
        if ($article->getOwner()->getUserId() !== $this->user->getUserId()) {
            throw new UserIsNotArticleOwner();
        }
    }

}