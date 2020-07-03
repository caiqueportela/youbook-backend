<?php

namespace App\Controller;

use App\Security\ApiVoter;
use App\Service\ActivityService;
use App\Validator\Exception\ActivityNotFound;
use App\Validator\Exception\ChapterNotFound;
use App\Validator\Exception\CourseNotFound;
use App\Validator\Exception\UserIsNotCourseOwner;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ActivityController extends ApiController
{

    /** @var ActivityService */
    private $activityService;

    /** @var TranslatorInterface */
    private $translator;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        ActivityService $activityService,
        TranslatorInterface $translator,
        SerializerInterface $serializer
    ) {
        $this->activityService = $activityService;
        $this->translator = $translator;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/course/{courseId}/chapter/{chapterId}/activity", name="Create activity", methods={"POST", "OPTIONS"})
     */
    public function createActivity($courseId, $chapterId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::AUTHOR_ROLE);

            $bodyData = json_decode($request->getContent(), true);

            $activity = $this->activityService->createActivity($courseId, $chapterId, $bodyData);

            return $this->setStatusCode(201)->response([
                'message' => $this->translator->trans('api.activity.create.success'),
                'activityId' => $activity->getActivityId(),
            ]);
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
     * @Route("/api/course/{courseId}/chapter/{chapterId}/activities", name="List activities", methods={"GET", "OPTIONS"})
     */
    public function listActivities($courseId, $chapterId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $activities = $this->activityService->listActivities($courseId, $chapterId);

            $serializedActivities = $this->serializer->serialize(
                $activities,
                'json'
            );

            return $this->respondSuccessWithData($serializedActivities);
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
     * @Route("/api/course/{courseId}/chapter/{chapterId}/activity/{activityId}", name="Detail activity", methods={"GET", "OPTIONS"})
     */
    public function getActivity($courseId, $chapterId, $activityId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $activity = $this->activityService->getActivity($courseId, $chapterId, $activityId);

            if (is_null($activity)) {
                return $this->respondNotFound($this->translator->trans('api.activity.get.not_found'));
            }

            $serializedChapter = $this->serializer->serialize(
                $activity,
                'json'
            );

            return $this->respondSuccessWithData($serializedChapter);
        } catch(CourseNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.course.get.not_found"));
        } catch(ChapterNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.chapter.get.not_found"));
        } catch(ActivityNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.activity.get.not_found"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/course/{courseId}/chapter/{chapterId}/activity/{activityId}", name="Delete activity", methods={"DELETE", "OPTIONS"})
     */
    public function deleteActivity($courseId, $chapterId, $activityId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::AUTHOR_ROLE);

            $activity = $this->activityService->getActivity($courseId, $chapterId, $activityId);

            if (is_null($activity)) {
                return $this->respondNotFound($this->translator->trans('api.activity.get.not_found'));
            }

            $this->activityService->deleteActivity($activity);

            return $this->respondWithSuccess($this->translator->trans('api.activity.delete.success'));
        } catch(CourseNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.course.get.not_found"));
        } catch(UserIsNotCourseOwner $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.course.user_not_owner"));
        } catch(ChapterNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.chapter.get.not_found"));
        } catch(ActivityNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.activity.get.not_found"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/course/{courseId}/chapter/{chapterId}/activity/{activityId}", name="Update activity", methods={"PATCH", "OPTIONS"})
     */
    public function updateActivity($courseId, $chapterId, $activityId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::AUTHOR_ROLE);

            $activity = $this->activityService->getActivity($courseId, $chapterId, $activityId);

            if (is_null($activity)) {
                return $this->respondNotFound($this->translator->trans('api.activity.get.not_found'));
            }

            $bodyData = json_decode($request->getContent(), true);

            $this->activityService->updateActivity($activity, $bodyData);

            return $this->respondWithSuccess($this->translator->trans('api.activity.update.success'));
        } catch(CourseNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.course.get.not_found"));
        } catch(UserIsNotCourseOwner $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.course.user_not_owner"));
        } catch(ChapterNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.chapter.get.not_found"));
        } catch(ActivityNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.activity.get.not_found"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

}