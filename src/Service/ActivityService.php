<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\ActivityRepository;
use Symfony\Component\Security\Core\Security;

class ActivityService
{

    /** @var User */
    private $user;

    /** @var ActivityRepository */
    private $activityRepository;

    /** @var YoubookPaginator */
    private $paginator;

    public function __construct(
        Security $security,
        ActivityRepository $activityRepository,
        YoubookPaginator $paginator
    ) {
        $this->user = $security->getUser();
        $this->activityRepository = $activityRepository;
        $this->paginator = $paginator;
    }

}