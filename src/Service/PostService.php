<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Validator\Exception\UserIsNotPostOwner;
use Symfony\Component\Security\Core\Security;

class PostService
{

    /** @var User */
    private $user;

    /** @var PostRepository */
    private $postRepository;

    /** @var YoubookPaginator */
    private $paginator;

    public function __construct(
        Security $security,
        PostRepository $postRepository,
        YoubookPaginator $paginator
    ) {
        $this->user = $security->getUser();
        $this->postRepository = $postRepository;
        $this->paginator = $paginator;
    }

    public function createPost($data)
    {
        // Validações e afins

        $post = new Post();
        $post->setMessage($data['message']);
        $post->setOwner($this->user);
        $this->postRepository->persistPost($post);
    }

    public function listPosts(int $page)
    {
        $posts = $this->postRepository->findPostsToPagination();

        $this->paginator->setCurrentPage($page);
        $this->paginator->setQuery($posts);

        return $this->paginator->paginate();
    }

    public function getPost($postId)
    {
        return $this->postRepository->findPost($postId);
    }

    public function deletePost(Post $post)
    {
        $this->validatePostOwner($post);

        $this->postRepository->deletePost($post);
    }

    public function updatePost(Post $post, $data)
    {
        $this->validatePostOwner($post);

        $post->setMessage($data['message']);
        $post->setUpdatedAt(new \DateTime());
        $this->postRepository->persistPost($post);
    }

    private function validatePostOwner(Post $post)
    {
        if ($post->getOwner()->getUserId() !== $this->user->getUserId()) {
            throw new UserIsNotPostOwner();
        }
    }

}