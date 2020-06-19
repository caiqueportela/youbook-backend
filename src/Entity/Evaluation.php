<?php

namespace App\Entity;

use App\Repository\EvaluationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tb_evaluation")
 * @ORM\Entity(repositoryClass=EvaluationRepository::class)
 */
class Evaluation
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $evaluationId;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=1, options={"default": 0.0})
     */
    private $score;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    public function __construct()
    {
        $this->score = 0.0;
    }

    public function getEvaluationId(): ?int
    {
        return $this->evaluationId;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(string $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

}
