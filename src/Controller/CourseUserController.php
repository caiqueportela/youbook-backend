<?php

namespace App\Controller;

use App\Security\ApiVoter;
use App\Service\CourseUserService;
use App\Validator\Exception\ActivityNotFound;
use App\Validator\Exception\CourseNotFound;
use App\Validator\Exception\UserAlreadyHaveTheCourse;
use App\Validator\Exception\UserIsNotRegisteredInCourse;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CourseUserController extends ApiController
{

    /** @var CourseUserService */
    private $courseUserService;

    /** @var TranslatorInterface */
    private $translator;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        CourseUserService $courseUserService,
        TranslatorInterface $translator,
        SerializerInterface $serializer
    ) {
        $this->courseUserService = $courseUserService;
        $this->translator = $translator;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/course/{courseId}/canAccess", name="Check if user can access the course", methods={"GET", "OPTIONS"})
     */
    public function userHasCourse($courseId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $userHasCourse = $this->courseUserService->userHasCourse($courseId);

            return $this->response($userHasCourse);
        } catch (CourseNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.course.get.not_found'));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/course/{courseId}/purchase", name="Purchase course", methods={"POST", "OPTIONS"})
     */
    public function purchaseCourse($courseId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $this->courseUserService->purchaseCourse($courseId);

            return $this->respondWithSuccess($this->translator->trans('api.user_course.purchase.success'));
        } catch (CourseNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.course.get.not_found'));
        } catch (UserAlreadyHaveTheCourse $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.user_course.purchase.already_have'));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/courses/purchased", name="List courses purchased", methods={"GET", "OPTIONS"})
     */
    public function listCoursesPurchased(Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $courses = $this->courseUserService->listCoursePurchased(
                $request->query->getInt('page', 1),
                $request->query->get('search', '')
            );

            $serializedCourses = $this->serializer->serialize(
                $courses,
                'json'
            );

            return $this->respondSuccessWithData($serializedCourses);
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/course/{courseId}/chapter/{chapterId}/activity/{activityId}/view", name="Mark activity view in corse", methods={"POST", "OPTIONS"})
     */
    public function markActivityView($courseId, $chapterId, $activityId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $courseUser = $this->courseUserService->markActivityView($courseId, $chapterId, $activityId);

            return $this->setStatusCode(200)->response([
                'message' => $this->translator->trans('api.user_course.view.success'),
                'percentage' => $courseUser->getPercentage(),
            ]);
        } catch (CourseNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.course.get.not_found'));
        } catch (ActivityNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.activity.get.not_found'));
        } catch (UserIsNotRegisteredInCourse $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.user_course.user_not_registered'));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/course/{courseId}/conclude", name="Conclude course", methods={"POST", "OPTIONS"})
     */
    public function concludeCourse($courseId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $this->courseUserService->concludeCourse($courseId);

            return $this->respondWithSuccess($this->translator->trans('api.user_course.conclude.success'));
        } catch (CourseNotFound $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.course.get.not_found'));
        } catch (UserIsNotRegisteredInCourse $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.user_course.user_not_registered'));
        } catch(\Exception $exception) {
            $code = $exception->getCode();
            if ($exception->getCode() === 0) {
                $code = 500;
            }
            return $this->setStatusCode($code)->respondWithErrors($exception->getMessage());
        }
    }

}