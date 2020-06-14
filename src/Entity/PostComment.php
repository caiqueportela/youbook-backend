<?php

namespace App\Entity;

use App\Repository\PostAnswerRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="tb_post_comment")
 * @ORM\Entity(repositoryClass="App\Repository\PostCommentRepository")
 * use JMS\Serializer\Annotation as Serializer;
 */
class PostComment
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="post_comment_id", type="integer")
     */
    private $postCommentId;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="user_id", nullable=false)
     */
    private $ownerId;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="postComments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="post_id", nullable=false)
     */
    private $post;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getPostCommentId(): ?int
    {
        return $this->postCommentId;
    }

    public function setPostCommentId(int $postCommentId): self
    {
        $this->postCommentId = $postCommentId;

        return $this;
    }

    public function getOwnerId(): ?User
    {
        return $this->ownerId;
    }

    public function setOwnerId(?User $ownerId): self
    {
        $this->ownerId = $ownerId;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

}
