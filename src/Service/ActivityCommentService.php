<?php

namespace App\Service;

use App\Entity\Activity;
use App\Entity\Comment;
use App\Entity\User;
use App\Repository\ActivityRepository;
use App\Repository\CommentRepository;
use App\Validator\Exception\ActivityNotFound;
use App\Validator\Exception\ChapterNotFound;
use App\Validator\Exception\UserIsNotCommentOwner;
use App\Validator\Exception\UserIsNotRegisteredInCourse;
use Symfony\Component\Security\Core\Security;

class ActivityCommentService
{

    /** @var User */
    private $user;

    /** @var ActivityRepository */
    private $activityRepository;

    /** @var CommentRepository */
    private $commentRepository;

    /** @var YoubookPaginator */
    private $paginator;

    /** @var ChapterService */
    private $chapterService;

    /** @var CourseUserService */
    private $courseUserService;

    public function __construct(
        Security $security,
        ActivityRepository $activityRepository,
        CommentRepository $commentRepository,
        YoubookPaginator $paginator,
        ChapterService $chapterService,
        CourseUserService $courseUserService
    ) {
        $this->user = $security->getUser();
        $this->activityRepository = $activityRepository;
        $this->commentRepository = $commentRepository;
        $this->paginator = $paginator;
        $this->chapterService = $chapterService;
        $this->courseUserService = $courseUserService;
    }

    public function createComment($courseId, $chapterId, $activityId, $data)
    {
        $activity = $this->findActivityById($courseId, $chapterId, $activityId);

        $this->validateUserInCourser($activity);

        $comment = new Comment();
        $comment->setMessage($data['message']);
        $comment->setOwner($this->user);
        $comment->setActivity($activity);
        $this->commentRepository->persistComment($comment);

        return $comment;
    }

    public function listComments($courseId, $chapterId, $activityId, int $page)
    {
        $activity = $this->findActivityById($courseId, $chapterId, $activityId);

        $this->validateUserInCourser($activity);

        $comments = $this->commentRepository->findActivityCommentsToPagination($activity->getActivityId());

        $this->paginator->setCurrentPage($page);
        $this->paginator->setQuery($comments);

        return $this->paginator->paginate();
    }

    public function getComment($courseId, $chapterId, $activityId, $commentId)
    {
        $activity = $this->findActivityById($courseId, $chapterId, $activityId);

        $this->validateUserInCourser($activity);

        return $this->commentRepository->findActivityComment($activityId, $commentId);
    }

    public function deleteComment(Comment $comment)
    {
        $this->validateCommentOwner($comment);

        $comment->setUpdatedAt(new \DateTime());
        $comment->setDeleted(true);
        $this->commentRepository->persistComment($comment);
    }

    public function updateComment(Comment $comment, $data)
    {
        $this->validateCommentOwner($comment);

        $comment->setMessage($data['message']);
        $comment->setUpdatedAt(new \DateTime());
        $this->commentRepository->persistComment($comment);
    }

    private function findActivityById($courseId, $chapterId, $activityId): Activity
    {
        $chapter = $this->chapterService->getChapter($courseId, $chapterId);

        if (is_null($chapter)) {
            throw new ChapterNotFound();
        }

        $activity = $this->activityRepository->findActivity($chapterId, $activityId);

        if (is_null($activity)) {
            throw new ActivityNotFound();
        }

        return $activity;
    }

    private function validateCommentOwner(Comment $comment)
    {
        if ($comment->getOwner()->getUserId() !== $this->user->getUserId()) {
            throw new UserIsNotCommentOwner();
        }
    }

    private function validateUserInCourser(Activity $activity)
    {
        if (!$this->courseUserService->isUserInCourse($activity->getChapter()->getCourse())) {
            throw new UserIsNotRegisteredInCourse();
        }
    }
    
}