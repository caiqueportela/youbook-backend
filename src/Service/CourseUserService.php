<?php

namespace App\Service;

use App\Entity\Activity;
use App\Entity\Course;
use App\Entity\CourseUser;
use App\Entity\CourseUserActivity;
use App\Entity\User;
use App\Repository\CourseRepository;
use App\Repository\CourseUserRepository;
use App\Validator\Exception\ActivityNotFound;
use App\Validator\Exception\CourseNotFound;
use App\Validator\Exception\UserAlreadyHaveTheCourse;
use App\Validator\Exception\UserIsNotRegisteredInCourse;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class CourseUserService
{

    const MINIMUM_PERCENTAGE_TO_CONCLUDE = 90;

    /** @var User */
    private $user;

    /** @var CourseUserRepository */
    private $courseUserRepository;

    /** @var YoubookPaginator */
    private $paginator;

    /** @var ActivityService */
    private $activityService;

    /** @var TranslatorInterface */
    private $translator;

    /** @var CourseRepository */
    private $courseRepository;

    public function __construct(
        Security $security,
        CourseUserRepository $courseUserRepository,
        YoubookPaginator $paginator,
        ActivityService $activityService,
        TranslatorInterface $translator,
        CourseRepository $courseRepository
    ) {
        $this->user = $security->getUser();
        $this->courseUserRepository = $courseUserRepository;
        $this->paginator = $paginator;
        $this->activityService = $activityService;
        $this->translator = $translator;
        $this->courseRepository = $courseRepository;
    }

    public function isUserInCourse(Course $course)
    {
        $courseUser = $this->courseUserRepository->findUserInCourse($this->user, $course);

        return !is_null($courseUser);
    }

    public function userHasCourse($courseId)
    {
        $course = $this->courseRepository->findCourse($courseId);

        if (is_null($course)) {
            throw new CourseNotFound();
        }

        return [
            'hasCourse' => $this->isUserInCourse($course),
        ];
    }

    public function purchaseCourse($courseId)
    {
        $course = $this->courseRepository->findCourse($courseId);

        if (is_null($course)) {
            throw new CourseNotFound();
        }

        if ($this->isUserInCourse($course)) {
            throw new UserAlreadyHaveTheCourse();
        }

        $courseUser = new CourseUser();
        $courseUser->setCourse($course);
        $courseUser->setOwner($this->user);

        $this->courseUserRepository->persistCourseUser($courseUser);
    }

    public function listCoursePurchased(int $page, string $search)
    {
        $courses = $this->courseUserRepository->findCoursesPurchasedToPagination($search, $this->user);

        $this->paginator->setCurrentPage($page);
        $this->paginator->setQuery($courses);

        return $this->paginator->paginate();
    }

    public function markActivityView($courseId, $chapterId, $activityId)
    {
        $course = $this->courseRepository->findCourse($courseId);

        if (is_null($course)) {
            throw new CourseNotFound();
        }

        if (!$this->isUserInCourse($course)) {
            throw new UserIsNotRegisteredInCourse();
        }

        $activity = $this->activityService->getActivity($courseId, $chapterId, $activityId);

        if (is_null($activity)) {
            throw new ActivityNotFound();
        }

        /** @var CourseUser $courseUser */
        $courseUser = $this->courseUserRepository->findUserInCourse($this->user, $course);

        if (!$this->userHasViewedActivity($courseUser, $activity)) {
            $courseUserActivity = new CourseUserActivity();
            $courseUserActivity->setActivity($activity);
            $courseUserActivity->setCourseUser($courseUser);
            $courseUser->addActivity($courseUserActivity);
            $this->courseUserRepository->persistCourseUser($courseUser);
            $this->calculateCoursePercentage($courseUser);
        }

        return $courseUser;
    }

    private function userHasViewedActivity(CourseUser $courseUser, Activity $activity)
    {
        $activities = $courseUser->getActivities();
        $hasViewed = false;

        foreach ($activities as $courseActivity) {
            if ($courseActivity->getActivity()->getActivityId() === $activity->getActivityId()) {
                $hasViewed = true;
            }
        }

        return $hasViewed;
    }

    private function calculateCoursePercentage(CourseUser $courseUser)
    {
        $countActivities = $this->countCourseActivities($courseUser->getCourse());

        $viewedActivities = count($courseUser->getActivities());

        $percentage = (100 / $countActivities) * $viewedActivities;

        $courseUser->setPercentage(intval($percentage, 10));
        $this->courseUserRepository->persistCourseUser($courseUser);
    }

    private function countCourseActivities(Course $course)
    {
        $count = 0;

        foreach ($course->getChapters() as $chapter) {
            $count += count($chapter->getActivities());
        }

        return $count;
    }

    public function concludeCourse($courseId)
    {
        $course = $this->courseRepository->findCourse($courseId);

        if (is_null($course)) {
            throw new CourseNotFound();
        }

        if (!$this->isUserInCourse($course)) {
            throw new UserIsNotRegisteredInCourse();
        }

        /** @var CourseUser $courseUser */
        $courseUser = $this->courseUserRepository->findUserInCourse($this->user, $course);

        $this->calculateCoursePercentage($courseUser);

        if ($courseUser->getPercentage() < self::MINIMUM_PERCENTAGE_TO_CONCLUDE) {
            throw new \Exception($this->translator->trans('api.user_course.conclude.percentage_error'), 400);
        }

        if (is_null($courseUser->getConcludedAt())) {
            $courseUser->setConcludedAt(new \DateTime());
            $this->courseUserRepository->persistCourseUser($courseUser);
        }
    }

}