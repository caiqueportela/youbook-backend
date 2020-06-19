<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\CommentRepository;
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

    /** @var CommentRepository */
    private $commentRepository;

    /** @var YoubookPaginator */
    private $paginator;

    public function __construct(
        Security $security,
        PostRepository $postRepository,
        CommentRepository $commentRepository,
        YoubookPaginator $paginator
    ) {
        $this->user = $security->getUser();
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
        $this->paginator = $paginator;
    }

    public function createComment($postId, $data)
    {
        $post = $this->findPostById($postId);

        $comment = new Comment();
        $comment->setMessage($data['message']);
        $comment->setOwner($this->user);
        $comment->setPost($post);
        $this->commentRepository->persistComment($comment);
    }

    public function listComments($postId, int $page)
    {
        /** @var Post $post */
        $post = $this->findPostById($postId);

        $comments = $this->commentRepository->findPostCommentsToPagination($post->getPostId());

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
        return $this->commentRepository->findComment($commentId);
    }

    public function deleteComment(Comment $comment)
    {
        $this->validateCommentOwner($comment);

        $comment->setUpdatedAt(new \DateTime());
        $comment->setDeleted(true);
        $this->commentRepository->persistComment($comment);
    }

    private function validateCommentOwner(Comment $comment)
    {
        if ($comment->getOwner()->getUserId() !== $this->user->getUserId()) {
            throw new UserIsNotCommentOwner();
        }
    }

    public function updateComment(Comment $comment, $data)
    {
        $this->validateCommentOwner($comment);

        $comment->setMessage($data['message']);
        $comment->setUpdatedAt(new \DateTime());
        $this->commentRepository->persistComment($comment);
    }

}