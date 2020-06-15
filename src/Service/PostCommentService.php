<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\PostComment;
use App\Entity\User;
use App\Repository\PostCommentRepository;
use App\Repository\PostRepository;
use App\Validator\Exception\PostNotFound;
use App\Validator\Exception\UserIsNotCommentOwner;
use Symfony\Component\Security\Core\Security;

class PostCommentService
{

    /** @var User */
    private $user;

    /** @var PostRepository */
    private $postRepository;

    /** @var PostCommentRepository */
    private $postCommentRepository;

    /** @var YoubookPaginator */
    private $paginator;

    public function __construct(
        Security $security,
        PostRepository $postRepository,
        PostCommentRepository $postCommentRepository,
        YoubookPaginator $paginator
    ) {
        $this->user = $security->getUser();
        $this->postRepository = $postRepository;
        $this->postCommentRepository = $postCommentRepository;
        $this->paginator = $paginator;
    }

    public function createComment($postId, $data)
    {
        $post = $this->findPostById($postId);

        $comment = new PostComment();
        $comment->setMessage($data['message']);
        $comment->setOwner($this->user);
        $comment->setPost($post);
        $this->postCommentRepository->persistComment($comment);
    }

    public function listComments($postId, int $page)
    {
        /** @var Post $post */
        $post = $this->findPostById($postId);

        $comments = $this->postCommentRepository->findPostCommentsToPagination($post->getPostId());

        $this->paginator->setCurrentPage($page);
        $this->paginator->setQuery($comments);

        return $this->paginator->paginate();
    }

    private function findPostById($postId)
    {
        $post = $this->postRepository->findPost($postId);

        if (is_null($post)) {
            throw new PostNotFound();
        }

        return $post;
    }

    public function getComment($commentId)
    {
        return $this->postCommentRepository->findComment($commentId);
    }

    public function deleteComment(PostComment $comment)
    {
        $this->validateCommentOwner($comment);

        $comment->setUpdatedAt(new \DateTime());
        $comment->setDeleted(true);
        $this->postCommentRepository->persistComment($comment);
    }

    private function validateCommentOwner(PostComment $comment)
    {
        if ($comment->getOwner()->getUserId() !== $this->user->getUserId()) {
            throw new UserIsNotCommentOwner();
        }
    }

    public function updateComment(PostComment $comment, $data)
    {
        $this->validateCommentOwner($comment);

        $comment->setMessage($data['message']);
        $comment->setUpdatedAt(new \DateTime());
        $this->postCommentRepository->persistComment($comment);
    }

}