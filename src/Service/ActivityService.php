<?php

namespace App\Service;

use App\Entity\Activity;
use App\Entity\User;
use App\Repository\ActivityRepository;
use App\Validator\Exception\ChapterNotFound;
use Symfony\Component\Security\Core\Security;

class ActivityService
{

    /** @var User */
    private $user;

    /** @var ActivityRepository */
    private $activityRepository;

    /** @var YoubookPaginator */
    private $paginator;

    /** @var ChapterService */
    private $chapterService;

    /** @var CourseService */
    private $courseService;

    public function __construct(
        Security $security,
        ActivityRepository $activityRepository,
        YoubookPaginator $paginator,
        ChapterService $chapterService,
        CourseService $courseService
    ) {
        $this->user = $security->getUser();
        $this->activityRepository = $activityRepository;
        $this->paginator = $paginator;
        $this->chapterService = $chapterService;
        $this->courseService = $courseService;
    }

    public function createActivity($courseId, $chapterId, $data): Activity
    {
        $chapter = $this->chapterService->getChapter($courseId, $chapterId);

        if (is_null($chapter)) {
            throw new ChapterNotFound();
        }

        $this->courseService->validateCourseOwner($chapter->getCourse());

        $activity = new Activity();
        $activity->setChapter($chapter);
        $activity->setTitle($data['title']);
        $activity->setContent($data['content']);

        $this->activityRepository->persistActivity($activity);

        return $activity;
    }

    public function listActivities($courseId, $chapterId)
    {
        $chapter = $this->chapterService->getChapter($courseId, $chapterId);

        if (is_null($chapter)) {
            throw new ChapterNotFound();
        }

        return $this->activityRepository->findActivities($chapterId);
    }

    public function getActivity($courseId, $chapterId, $activityId)
    {
        $chapter = $this->chapterService->getChapter($courseId, $chapterId);

        if (is_null($chapter)) {
            throw new ChapterNotFound();
        }

        return $this->activityRepository->findActivity($chapterId, $activityId);
    }

    public function deleteActivity(Activity $activity)
    {
        $this->courseService->validateCourseOwner($activity->getChapter()->getCourse());

        $this->activityRepository->deleteActivity($activity);

    }

    public function updateActivity(Activity $activity, $data)
    {
        $this->courseService->validateCourseOwner($activity->getChapter()->getCourse());

        $activity->setTitle($data['title']);
        $activity->setContent($data['content']);
        $activity->setUpdatedAt(new \DateTime());

        $this->activityRepository->persistActivity($activity);
    }

}