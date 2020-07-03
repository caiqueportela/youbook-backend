<?php

namespace App\Controller;

use App\Security\ApiVoter;
use App\Service\ChapterService;
use App\Validator\Exception\ChapterNotFound;
use App\Validator\Exception\CourseNotFound;
use App\Validator\Exception\UserIsNotCourseOwner;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChapterController extends ApiController
{

    /** @var ChapterService */
    private $chapterService;

    /** @var TranslatorInterface */
    private $translator;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        ChapterService $chapterService,
        TranslatorInterface $translator,
        SerializerInterface $serializer
    ) {
        $this->chapterService = $chapterService;
        $this->translator = $translator;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/course/{courseId}/chapter", name="Create course chapter", methods={"POST", "OPTIONS"})
     */
    public function createChapter($courseId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::AUTHOR_ROLE);

            $bodyData = json_decode($request->getContent(), true);

            $chapter = $this->chapterService->createChapter($courseId, $bodyData);

            return $this->setStatusCode(201)->response([
                'message' => $this->translator->trans('api.chapter.create.success'),
                'chapterId' => $chapter->getChapterId(),
            ]);
        } catch(CourseNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.course.get.not_found"));
        } catch(UserIsNotCourseOwner $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.course.user_not_owner"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/course/{courseId}/chapters", name="List course chapters", methods={"GET", "OPTIONS"})
     */
    public function listChapters($courseId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $chapters = $this->chapterService->listChapters($courseId);

            $serializedChapters = $this->serializer->serialize(
                $chapters,
                'json'
            );

            return $this->respondSuccessWithData($serializedChapters);
        } catch(CourseNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.course.get.not_found"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/course/{courseId}/chapter/{chapterId}", name="Detail chapter", methods={"GET", "OPTIONS"})
     */
    public function getChapter($courseId, $chapterId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $chapter = $this->chapterService->getChapter($courseId, $chapterId);

            if (is_null($chapter)) {
                return $this->respondNotFound($this->translator->trans('api.chapter.get.not_found'));
            }

            $serializedChapter = $this->serializer->serialize(
                $chapter,
                'json'
            );

            return $this->respondSuccessWithData($serializedChapter);
        } catch(CourseNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.course.get.not_found"));
        } catch(ChapterNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.chapter.get.not_found"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/course/{courseId}/chapter/{chapterId}", name="Delete chapter", methods={"DELETE", "OPTIONS"})
     */
    public function deleteChapter($courseId, $chapterId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::AUTHOR_ROLE);

            $chapter = $this->chapterService->getChapter($courseId, $chapterId);

            if (is_null($chapter)) {
                return $this->respondNotFound($this->translator->trans('api.chapter.get.not_found'));
            }

            $this->chapterService->deleteChapter($chapter);

            return $this->respondWithSuccess($this->translator->trans('api.chapter.delete.success'));
        } catch(CourseNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.course.get.not_found"));
        } catch(UserIsNotCourseOwner $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.course.user_not_owner"));
        } catch(ChapterNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.chapter.get.not_found"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/course/{courseId}/chapter/{chapterId}", name="Update chapter", methods={"PATCH", "OPTIONS"})
     */
    public function updateChapter($courseId, $chapterId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::AUTHOR_ROLE);

            $chapter = $this->chapterService->getChapter($courseId, $chapterId);

            if (is_null($chapter)) {
                return $this->respondNotFound($this->translator->trans('api.chapter.get.not_found'));
            }

            $bodyData = json_decode($request->getContent(), true);

            $this->chapterService->updateChapter($chapter, $bodyData);

            return $this->respondWithSuccess($this->translator->trans('api.chapter.update.success'));
        } catch(CourseNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.course.get.not_found"));
        } catch(UserIsNotCourseOwner $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.course.user_not_owner"));
        } catch(ChapterNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.chapter.get.not_found"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

}