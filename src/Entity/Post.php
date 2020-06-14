<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="tb_post")
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="post_id", type="integer")
     */
    private $postId;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="user_id", nullable=false)
     */
    private $owner;

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
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $deleted;

    /**
     * @var PostComment[]
     * @ORM\OneToMany(targetEntity="PostComment", mappedBy="post", orphanRemoval=true)
     */
    private $postComments;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->postComments = new ArrayCollection();
        $this->deleted = false;
    }

    public function getPostId(): ?int
    {
        return $this->postId;
    }

    public function setPostId(int $postId): self
    {
        $this->postId = $postId;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

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

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     */
    public function setDeleted(bool $deleted): void
    {
        $this->deleted = $deleted;
    }

    /**
     * @return Collection|PostComment[]
     */
    public function getPostComments(): Collection
    {
        return $this->postComments;
    }

    public function addPostAnswer(PostComment $postAnswer): self
    {
        if (!$this->postComments->contains($postAnswer)) {
            $this->postComments[] = $postAnswer;
            $postAnswer->setPost($this);
        }

        return $this;
    }

    public function removePostAnswer(PostComment $postAnswer): self
    {
        if ($this->postComments->contains($postAnswer)) {
            $this->postComments->removeElement($postAnswer);
            // set the owning side to null (unless already changed)
            if ($postAnswer->getPost() === $this) {
                $postAnswer->setPost(null);
            }
        }

        return $this;
    }

}
