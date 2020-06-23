<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\ChapterRepository;
use Symfony\Component\Security\Core\Security;

class ChapterService
{

    /** @var User */
    private $user;

    /** @var ChapterRepository */
    private $chapterRepository;

    /** @var YoubookPaginator */
    private $paginator;

    public function __construct(
        Security $security,
        ChapterRepository $chapterRepository,
        YoubookPaginator $paginator
    ) {
        $this->user = $security->getUser();
        $this->chapterRepository = $chapterRepository;
        $this->paginator = $paginator;
    }

}