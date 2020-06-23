<?php

namespace App\Controller;

use App\Service\ActivityService;
use JMS\Serializer\SerializerInterface;
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

}