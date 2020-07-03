<?php

namespace App\Entity;

use App\Repository\CourseUserActivityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tb_course_user_activity")
 * @ORM\Entity(repositoryClass=CourseUserActivityRepository::class)
 */
class CourseUserActivity
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $courseUserActivityId;

    /**
     * @ORM\ManyToOne(targetEntity=CourseUser::class, inversedBy="activities")
     * @ORM\JoinColumn(name="course_user_id", referencedColumnName="course_user_id", nullable=false)
     */
    private $courseUser;

    /**
     * @ORM\ManyToOne(targetEntity=Activity::class)
     * @ORM\JoinColumn(name="activity_id", referencedColumnName="activity_id", nullable=false)
     */
    private $activity;

    /**
     * @ORM\Column(type="datetime")
     */
    private $viewedAt;

    public function __construct()
    {
        $this->viewedAt = new \DateTime();
    }

    public function getCourseUserActivityId(): ?int
    {
        return $this->courseUserActivityId;
    }

    public function getCourseUser(): ?CourseUser
    {
        return $this->courseUser;
    }

    public function setCourseUser(?CourseUser $courseUser): self
    {
        $this->courseUser = $courseUser;

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getViewedAt(): ?\DateTimeInterface
    {
        return $this->viewedAt;
    }

    public function setViewedAt(\DateTimeInterface $viewedAt): self
    {
        $this->viewedAt = $viewedAt;

        return $this;
    }

}
