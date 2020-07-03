<?php

namespace App\Service;

use App\Entity\Course;
use App\Entity\User;
use App\Repository\CourseUserRepository;
use Symfony\Component\Security\Core\Security;

class CourseUserService
{

    /** @var User */
    private $user;

    /** @var CourseUserRepository */
    private $courseUserRepository;

    /** @var YoubookPaginator */
    private $paginator;

    public function __construct(
        Security $security,
        CourseUserRepository $courseUserRepository,
        YoubookPaginator $paginator
    ) {
        $this->user = $security->getUser();
        $this->courseUserRepository = $courseUserRepository;
        $this->paginator = $paginator;
    }

    public function isUserInCourse(Course $course)
    {
        $courseUser = $this->courseUserRepository->findUserInCourse($this->user, $course);

        return !is_null($courseUser);
    }

}