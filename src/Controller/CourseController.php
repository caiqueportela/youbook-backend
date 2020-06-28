<?php

namespace App\Controller;

use App\Security\ApiVoter;
use App\Service\CourseService;
use App\Validator\Exception\SubjectNotFound;
use App\Validator\Exception\UserIsNotCourseOwner;
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
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $bodyData = json_decode($request->getContent(), true);

            $this->courseService->createCourse($bodyData);

            return $this->respondCreated($this->translator->trans('api.course.create.success'));
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
                return $this->respondNotFound($this->translator->trans('api.article.get.not_found'));
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
    public function deleteArticle($courseId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $course = $this->courseService->getCourse($courseId);

            if (is_null($course)) {
                return $this->respondNotFound($this->translator->trans('api.course.get.not_found'));
            }

            $this->courseService->deleteCourse($course);

            return $this->respondWithSuccess($this->translator->trans('api.course.delete.success'));
        } catch(UserIsNotCourseOwner $exception) {
            return $this->setStatusCode($exception->getCode())->respondWithErrors($this->translator->trans("api.course.delete.user_not_owner"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/course/{courseId}", name="Update course", methods={"PATCH", "OPTIONS"})
     */
    public function updateArticle($courseId, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $course = $this->courseService->getCourse($courseId);

            if (is_null($course)) {
                return $this->respondNotFound($this->translator->trans('api.course.get.not_found'));
            }

            $bodyData = json_decode($request->getContent(), true);

            $this->courseService->updateCourse($course, $bodyData);

            return $this->respondWithSuccess($this->translator->trans('api.course.updated.success'));
        } catch(UserIsNotCourseOwner $exception) {
            return $this->setStatusCode($exception->getCode())->respondWithErrors($this->translator->trans("api.course.delete.user_not_owner"));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

}