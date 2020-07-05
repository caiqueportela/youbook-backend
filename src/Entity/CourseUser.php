<?php

namespace App\Entity;

use App\Repository\CourseUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tb_course_user")
 * @ORM\Entity(repositoryClass=CourseUserRepository::class)
 */
class CourseUser
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $courseUserId;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="user_id", nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class)
     * @ORM\JoinColumn(name="course_id", referencedColumnName="course_id", nullable=false)
     */
    private $course;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $percentage;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $concludedAt;

    /**
     * @ORM\OneToMany(targetEntity=CourseUserActivity::class, cascade={"persist"}, mappedBy="courseUser", orphanRemoval=true)
     * @ORM\JoinColumn(name="course_user_activity_id", referencedColumnName="course_user_activity_id")
     */
    private $activities;

    public function __construct()
    {
        $this->percentage = 0;
        $this->startedAt = new \DateTime();
        $this->activities = new ArrayCollection();
    }

    public function getCourseUserId(): ?int
    {
        return $this->courseUserId;
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

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getPercentage(): ?int
    {
        return $this->percentage;
    }

    public function setPercentage(int $percentage): self
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeInterface $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getConcludedAt(): ?\DateTimeInterface
    {
        return $this->concludedAt;
    }

    public function setConcludedAt(?\DateTimeInterface $concludedAt): self
    {
        $this->concludedAt = $concludedAt;

        return $this;
    }

    /**
     * @return Collection|CourseUserActivity[]
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    /**
     * @param CourseUserActivity $activity
     * @return $this
     */
    public function addActivity(CourseUserActivity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
            $activity->setCourseUser($this);
        }

        return $this;
    }

    /**
     * @param CourseUserActivity $activity
     * @return $this
     */
    public function removeActivity(CourseUserActivity $activity): self
    {
        if ($this->activities->contains($activity)) {
            $this->activities->removeElement($activity);
            if ($activity->getCourseUser() === $this) {
                $activity->setCourseUser(null);
            }
        }

        return $this;
    }

}
