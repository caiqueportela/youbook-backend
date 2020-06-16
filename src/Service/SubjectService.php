<?php

namespace App\Service;

use App\Entity\Subject;
use App\Entity\User;
use App\Repository\SubjectRepository;
use Symfony\Component\Security\Core\Security;

class SubjectService
{

    /** @var User */
    private $user;

    /** @var SubjectRepository */
    private $subjectRepository;

    /** @var YoubookPaginator */
    private $paginator;

    public function __construct(
        Security $security,
        SubjectRepository $subjectRepository,
        YoubookPaginator $paginator
    ) {
        $this->user = $security->getUser();
        $this->subjectRepository = $subjectRepository;
        $this->paginator = $paginator;
    }

    public function createSubject($data)
    {
        $subject = new Subject();
        $subject->setName($data['name']);
        $subject->setDescription($data['description']);
        $this->subjectRepository->persistSubject($subject);
    }

    public function listSubjects()
    {
        return $this->subjectRepository->findSubjects();
    }

}