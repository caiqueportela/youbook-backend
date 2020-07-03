<?php

namespace App\Service;

use App\Entity\Course;
use App\Entity\Evaluation;
use App\Entity\User;
use App\Repository\CourseRepository;
use App\Repository\CourseUserRepository;
use App\Repository\SubjectRepository;
use App\Validator\Exception\SubjectNotFound;
use App\Validator\Exception\UserIsNotCourseOwner;
use App\Validator\Exception\UserIsNotRegisteredInCourse;
use Symfony\Component\Security\Core\Security;

class CourseService
{

    /** @var User */
    private $user;

    /** @var CourseRepository */
    private $courseRepository;

    /** @var SubjectRepository */
    private $subjectRepository;

    /** @var YoubookPaginator */
    private $paginator;

    /** @var CourseUserRepository */
    private $courseUserRepository;

    public function __construct(
        Security $security,
        CourseRepository $courseRepository,
        SubjectRepository $subjectRepository,
        YoubookPaginator $paginator,
        CourseUserRepository $courseUserRepository
    ) {
        $this->user = $security->getUser();
        $this->courseRepository = $courseRepository;
        $this->subjectRepository = $subjectRepository;
        $this->paginator = $paginator;
        $this->courseUserRepository = $courseUserRepository;
    }

    public function createCourse($data)
    {
        $subject = $this->findSubjectById($data['subject']);

        $course = new Course();
        $course->setOwner($this->user);
        $course->setSubject($subject);
        $course->setTitle($data['title']);
        $course->setSubtitle($data['subtitle']);
        $course->setDescription($data['description']);

        $groupUser = $this->user->getGroupUser()->first();
        if (!$groupUser) {
            throw new \Exception('User need a group');
        }

        $course->setGroup($groupUser->getGroup());
        $this->courseRepository->persistCourse($course);

        return $course;
    }

    private function findSubjectById($subjectId)
    {
        $subject = $this->subjectRepository->findSubject($subjectId);

        if (is_null($subject)) {
            throw new SubjectNotFound();
        }

        return $subject;
    }

    public function listCourses(int $page, string $search = null)
    {
        $courses = $this->courseRepository->findCoursesToPagination($search);

        $this->paginator->setCurrentPage($page);
        $this->paginator->setQuery($courses);

        return $this->paginator->paginate();
    }

    public function getCourse($courseId)
    {
        return $this->courseRepository->findCourse($courseId);
    }

    public function deleteCourse(Course $course)
    {
        $this->validateCourseOwner($course);

        $this->courseRepository->deleteCourse($course);
    }

    public function updateCourse(Course $course, $data)
    {
        $this->validateCourseOwner($course);

        $subject = $this->findSubjectById($data['subject']);

        $course->setSubject($subject);
        $course->setTitle($data['title']);
        $course->setSubtitle($data['subtitle']);
        $course->setDescription($data['description']);
        $course->setUpdatedAt(new \DateTime());
        $this->courseRepository->persistCourse($course);
    }

    public function validateCourseOwner(Course $course)
    {
        if ($course->getOwner()->getUserId() !== $this->user->getUserId()) {
            throw new UserIsNotCourseOwner();
        }
    }

    public function evaluateCourse(Course $course, $data)
    {
        if (!$this->isUserInCourse($course)) {
            throw new UserIsNotRegisteredInCourse();
        }

        $evaluation = new Evaluation();
        $evaluation->setUser($this->user);
        $evaluation->setScore(intval($data['score'], 10));
        $evaluation->setComment($data['comment']);

        $course->addEvaluation($evaluation);

        $this->courseRepository->persistCourse($course);
    }

    public function getCourseEvaluations(Course $course)
    {
        return $course->getEvaluations();
    }

    private function isUserInCourse(Course $course)
    {
        $courseUser = $this->courseUserRepository->findUserInCourse($this->user, $course);

        return !is_null($courseUser);
    }

}