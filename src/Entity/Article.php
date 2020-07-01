it status
<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="tb_article")
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{

    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="article_id", type="integer")
     */
    private $articleId;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="user_id", nullable=false)
     */
    private $owner;

    /**
     * @var Subject
     * @ORM\ManyToOne(targetEntity=Subject::class)
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="subject_id", nullable=false)
     */
    private $subject;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subtitle;

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
     * @var string
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default": false})
     * @Serializer\Exclude()
     */
    private $deleted;

    /**
     * @var Group
     * @ORM\ManyToOne(targetEntity=Group::class)
     * @ORM\JoinColumn(name="group_id", referencedColumnName="group_id", nullable=false)
     */
    private $group;

    /**
     * @ORM\ManyToMany(targetEntity="Evaluation")
     * @ORM\JoinTable(name="tb_rel_article_evaluation",
     *      joinColumns={@ORM\JoinColumn(name="article_id", referencedColumnName="article_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="evaluation_id", referencedColumnName="evaluation_id", unique=true)}
     * )
     * @Serializer\Exclude()
     */
    private $evaluations;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="article", cascade={"persist"}, orphanRemoval=true)
     * @Serializer\Exclude()
     */
    private $comments;

    /**
     * Article constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->deleted = false;
        $this->evaluations = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getArticleId(): ?int
    {
        return $this->articleId;
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

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(?Subject $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): self
    {
        $this->subtitle = $subtitle;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): self
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return Collection|Evaluation[]
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    /**
     * @param Evaluation $evaluation
     * @return $this
     */
    public function addEvaluation(Evaluation $evaluation): self
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations[] = $evaluation;
        }

        return $this;
    }

    /**
     * @param Evaluation $evaluation
     * @return $this
     */
    public function removeEvaluation(Evaluation $evaluation): self
    {
        if ($this->evaluations->contains($evaluation)) {
            $this->evaluations->removeElement($evaluation);
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setArticle($this);
        }

        return $this;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

}
