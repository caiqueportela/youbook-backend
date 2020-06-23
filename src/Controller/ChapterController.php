<?php

namespace App\Controller;

use App\Service\ChapterService;
use JMS\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChapterController extends ApiController
{

    /** @var ChapterService */
    private $chapterService;

    /** @var TranslatorInterface */
    private $translator;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        ChapterService $chapterService,
        TranslatorInterface $translator,
        SerializerInterface $serializer
    ) {
        $this->chapterService = $chapterService;
        $this->translator = $translator;
        $this->serializer = $serializer;
    }

}