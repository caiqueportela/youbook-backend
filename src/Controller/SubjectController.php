<?php

namespace App\Controller;

use App\Security\ApiVoter;
use App\Service\SubjectService;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubjectController extends ApiController
{

    /** @var SubjectService */
    private $subjectService;

    /** @var TranslatorInterface */
    private $translator;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        SubjectService $subjectService,
        TranslatorInterface $translator,
        SerializerInterface $serializer
    ) {
        $this->subjectService = $subjectService;
        $this->translator = $translator;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/subject", name="Create subject", methods={"POST", "OPTIONS"})
     */
    public function createSubject(Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::ADMIN_ROLE);

            $bodyData = json_decode($request->getContent(), true);

            $this->subjectService->createSubject($bodyData);

            return $this->respondCreated($this->translator->trans('api.subject.create.success'));
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/api/subjects", name="List subjects", methods={"GET", "OPTIONS"})
     */
    public function listSubjects(Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(ApiVoter::USER_ROLE);

            $subject = $this->subjectService->listSubjects();

            $serializedSubjects = $this->serializer->serialize(
                $subject,
                'json'
            );

            return $this->respondSuccessWithData($serializedSubjects);
        } catch(\Exception $exception) {
            return $this->setStatusCode(500)->respondWithErrors($exception->getMessage());
        }
    }

}