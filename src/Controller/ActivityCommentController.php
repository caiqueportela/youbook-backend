<?php

namespace App\Controller;

use App\Service\ActivityCommentService;
use App\Validator\Exception\ActivityNotFound;
use App\Validator\Exception\ChapterNotFound;
use App\Validator\Exception\CourseNotFound;
use App\Validator\Exception\UserIsNotCommentOwner;
use App\Validator\Exception\UserIsNotRegisteredInCourse;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ActivityCommentController extends ApiController
{

    /** @var ActivityCommentService */
    private $activityCommentService;

    /** @var TranslatorInterface */
    private $translator;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        ActivityCommentService $activityCommentService,
        TranslatorInterface $translator,
        SerializerInterface $serializer
    ) {
        $this->activityCommentService = $activityCommentService;
        $this->translator = $translator;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/course/{courseId}/chapter/{chapterId}/activity/{activityId}/comment", name="Create comment in activity", methods={"POST", "OPTIONS"})
     */
    public function createActivityComment($courseId, $chapterId, $activityId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $bodyData = json_decode($request->getContent(), true);

            $comment = $this->activityCommentService->createComment($courseId, $chapterId, $activityId, $bodyData);

            return $this->setStatusCode(201)->response([
                'message' => $this->translator->trans('api.comment.create.success'),
                'commentId' => $comment->getCommentId(),
            ]);
        } catch(ActivityNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.activity.get.not_found'));
        } catch(CourseNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.course.get.not_found'));
        } catch(ChapterNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.chapter.get.not_found'));
        } catch (UserIsNotRegisteredInCourse $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.user_course.user_not_registered'));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/course/{courseId}/chapter/{chapterId}/activity/{activityId}/comments", name="List comments of activity", methods={"GET", "OPTIONS"})
     */
    public function listComments($courseId, $chapterId, $activityId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $comments = $this->activityCommentService->listComments($courseId, $chapterId, $activityId, $request->query->getInt('page', 1));

            $serializedComments = $this->serializer->serialize(
                $comments,
                'json'
            );

            return $this->respondSuccessWithData($serializedComments);
        } catch(ActivityNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.activity.get.not_found'));
        } catch(CourseNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.course.get.not_found'));
        } catch(ChapterNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.chapter.get.not_found'));
        } catch (UserIsNotRegisteredInCourse $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.user_course.user_not_registered'));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/course/{courseId}/chapter/{chapterId}/activity/{activityId}/comment/{commentId}", name="Get activity comment", methods={"GET", "OPTIONS"})
     */
    public function getComment($courseId, $chapterId, $activityId, $commentId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $comment = $this->activityCommentService->getComment($courseId, $chapterId, $activityId, $commentId);

            if (is_null($comment)) {
                return $this->respondNotFound($this->translator->trans('api.comment.get.not_found'));
            }

            $serializedComment = $this->serializer->serialize(
                $comment,
                'json'
            );

            return $this->respondSuccessWithData($serializedComment);
        } catch(ActivityNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.activity.get.not_found'));
        } catch(CourseNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.course.get.not_found'));
        } catch(ChapterNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.chapter.get.not_found'));
        } catch (UserIsNotRegisteredInCourse $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.user_course.user_not_registered'));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/course/{courseId}/chapter/{chapterId}/activity/{activityId}/comment/{commentId}", name="Delete activity comment", methods={"DELETE", "OPTIONS"})
     */
    public function deleteComment($courseId, $chapterId, $activityId, $commentId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $comment = $this->activityCommentService->getComment($courseId, $chapterId, $activityId, $commentId);

            if (is_null($comment)) {
                return $this->respondNotFound($this->translator->trans('api.comment.get.not_found'));
            }

            $this->activityCommentService->deleteComment($comment);

            return $this->respondWithSuccess($this->translator->trans('api.comment.delete.success'));
        } catch(ActivityNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.activity.get.not_found'));
        } catch(CourseNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.course.get.not_found'));
        } catch(ChapterNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.chapter.get.not_found'));
        } catch (UserIsNotRegisteredInCourse $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.user_course.user_not_registered'));
        } catch(UserIsNotCommentOwner $exception) {
            return $this->setStatusCode($exception->getCode())->respondWithErrors($this->translator->trans("api.comment.user_not_owner"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/course/{courseId}/chapter/{chapterId}/activity/{activityId}/comment/{commentId}", name="Update a activity comment", methods={"PATCH", "OPTIONS"})
     */
    public function updateComment($courseId, $chapterId, $activityId, $commentId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $comment = $this->activityCommentService->getComment($courseId, $chapterId, $activityId, $commentId);

            if (is_null($comment)) {
                return $this->respondNotFound($this->translator->trans('api.comment.get.not_found'));
            }

            $bodyData = json_decode($request->getContent(), true);

            $this->activityCommentService->updateComment($comment, $bodyData);

            return $this->respondWithSuccess($this->translator->trans('api.comment.update.success'));
        } catch(ActivityNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.activity.get.not_found'));
        } catch(CourseNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.course.get.not_found'));
        } catch(ChapterNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.chapter.get.not_found'));
        } catch (UserIsNotRegisteredInCourse $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.user_course.user_not_registered'));
        } catch(UserIsNotCommentOwner $exception) {
            return $this->setStatusCode($exception->getCode())->respondWithErrors($this->translator->trans("api.comment.user_not_owner"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

}