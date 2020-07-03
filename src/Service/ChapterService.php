<?php

namespace App\Service;

use App\Entity\Chapter;
use App\Entity\User;
use App\Repository\ChapterRepository;
use App\Validator\Exception\CourseNotFound;
use Symfony\Component\Security\Core\Security;

class ChapterService
{

    /** @var User */
    private $user;

    /** @var ChapterRepository */
    private $chapterRepository;

    /** @var YoubookPaginator */
    private $paginator;

    /** @var CourseService */
    private $courseService;

    public function __construct(
        Security $security,
        ChapterRepository $chapterRepository,
        YoubookPaginator $paginator,
        CourseService $courseService
    ) {
        $this->user = $security->getUser();
        $this->chapterRepository = $chapterRepository;
        $this->paginator = $paginator;
        $this->courseService = $courseService;
    }

    public function createChapter($courseId, $data): Chapter
    {
        $course = $this->courseService->getCourse($courseId);

        if (is_null($course)) {
            throw new CourseNotFound();
        }

        $this->courseService->validateCourseOwner($course);

        $chapter = new Chapter();
        $chapter->setCourse($course);
        $chapter->setTitle($data['title']);
        $chapter->setDescription($data['description']);

        $this->chapterRepository->persistChapter($chapter);

        return $chapter;
    }

    public function listChapters($courseId)
    {
        $course = $this->courseService->getCourse($courseId);

        if (is_null($course)) {
            throw new CourseNotFound();
        }

        return $this->chapterRepository->findChapters($courseId);
    }

    public function getChapter($courseId, $chapterId): Chapter
    {
        $course = $this->courseService->getCourse($courseId);

        if (is_null($course)) {
            throw new CourseNotFound();
        }

        return $this->chapterRepository->findChapter($courseId, $chapterId);
    }

    public function deleteChapter(Chapter $chapter)
    {
        $this->courseService->validateCourseOwner($chapter->getCourse());

        $this->chapterRepository->deleteChapter($chapter);
    }

    public function updateChapter(Chapter $chapter, $data)
    {
        $this->courseService->validateCourseOwner($chapter->getCourse());

        $chapter->setTitle($data['title']);
        $chapter->setDescription($data['description']);
        $chapter->setUpdatedAt(new \DateTime());

        $this->chapterRepository->persistChapter($chapter);
    }

}