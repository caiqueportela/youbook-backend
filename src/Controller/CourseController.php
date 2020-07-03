<?php

namespace App\Controller;

use App\Security\ApiVoter;
use App\Service\CourseService;
use App\Validator\Exception\SubjectNotFound;
use App\Validator\Exception\UserIsNotCourseOwner;
use App\Validator\Exception\UserIsNotRegisteredInCourse;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CourseController extends ApiController
{

    /** @var CourseService */
    private $courseService;

    /** @var TranslatorInterface */
    private $translator;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        CourseService $courseService,
        TranslatorInterface $translator,
        SerializerInterface $serializer
    ) {
        $this->courseService = $courseService;
        $this->translator = $translator;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/course", name="Create course", methods={"POST", "OPTIONS"})
     */
    public function createCourse(Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::AUTHOR_ROLE);

            $bodyData = json_decode($request->getContent(), true);

            $course = $this->courseService->createCourse($bodyData);

            return $this->setStatusCode(201)->response([
                'message' => $this->translator->trans('api.course.create.success'),
                'courseId' => $course->getCourseId(),
            ]);
        } catch(SubjectNotFound $exception) {
            return $this->setStatusCode($exception->getCode())->respondWithErrors($this->translator->trans("api.subject.get.not_found"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/courses", name="List courses", methods={"GET", "OPTIONS"})
     */
    public function listCourses(Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $courses = $this->courseService->listCourses(
                $request->query->getInt('page', 1),
                $request->query->get('search')
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
     * @Route("/api/course/{courseId}", name="Detail course", methods={"GET", "OPTIONS"})
     */
    public function getCourse($courseId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $course = $this->courseService->getCourse($courseId);

            if (is_null($course)) {
                return $this->respondNotFound($this->translator->trans('api.course.get.not_found'));
            }

            $serializedCourse = $this->serializer->serialize(
                $course,
                'json'
            );

            return $this->respondSuccessWithData($serializedCourse);
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/course/{courseId}", name="Delete course", methods={"DELETE", "OPTIONS"})
     */
    public function deleteCourse($courseId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::AUTHOR_ROLE);

            $course = $this->courseService->getCourse($courseId);

            if (is_null($course)) {
                return $this->respondNotFound($this->translator->trans('api.course.get.not_found'));
            }

            $this->courseService->deleteCourse($course);

            return $this->respondWithSuccess($this->translator->trans('api.course.delete.success'));
        } catch(UserIsNotCourseOwner $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.course.user_not_owner"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/course/{courseId}", name="Update course", methods={"PATCH", "OPTIONS"})
     */
    public function updateCourse($courseId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::AUTHOR_ROLE);

            $course = $this->courseService->getCourse($courseId);

            if (is_null($course)) {
                return $this->respondNotFound($this->translator->trans('api.course.get.not_found'));
            }

            $bodyData = json_decode($request->getContent(), true);

            $this->courseService->updateCourse($course, $bodyData);

            return $this->respondWithSuccess($this->translator->trans('api.course.update.success'));
        } catch(UserIsNotCourseOwner $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans("api.course.user_not_owner"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/course/{courseId}/evaluate", name="Evaluate course", methods={"POST", "OPTIONS"})
     */
    public function evaluateCourse($courseId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $course = $this->courseService->getCourse($courseId);

            if (is_null($course)) {
                return $this->respondNotFound($this->translator->trans('api.course.get.not_found'));
            }

            $bodyData = json_decode($request->getContent(), true);

            $this->courseService->evaluateCourse($course, $bodyData);

            return $this->respondWithSuccess($this->translator->trans('api.course.evaluate.success'));
        } catch (UserIsNotRegisteredInCourse $exception) {
            return $this->setStatusCode($exception->getCode())
                ->respondWithErrors($this->translator->trans('api.user_course.user_not_registered'));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/course/{courseId}/evaluations", name="List evaluations course", methods={"GET", "OPTIONS"})
     */
    public function listCourseEvaluations($courseId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $course = $this->courseService->getCourse($courseId);

            if (is_null($course)) {
                return $this->respondNotFound($this->translator->trans('api.course.get.not_found'));
            }

            $evaluations = $this->courseService->getCourseEvaluations($course);

            $serializedEvaluations = $this->serializer->serialize(
                $evaluations,
                'json'
            );

            return $this->respondSuccessWithData($serializedEvaluations);
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

}