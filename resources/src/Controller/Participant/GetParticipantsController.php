<?php
namespace App\Controller\Event;

use App\Entity\Event;
use App\Manager\EventManager;
use App\Manager\ParticipantManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class GetParticipantsController extends AbstractController
{
    /** @var ParticipantManager */
    private $participantManager;

    /** @var EventManager */
    private $eventManager;

    /** @var SerializerInterface */
    private $serializer;

    /**
     * @param ParticipantManager $participantManager
     * @param EventManager $eventManager
     * @param SerializerInterface $serializer
     */
    public function __construct(
        ParticipantManager $participantManager,
        EventManager $eventManager,
        SerializerInterface $serializer
    ) {
        $this->participantManager = $participantManager;
        $this->serializer = $serializer;
    }

    /**
     * Get participants list by event id
     *
     * @Route("/api/participants/{eventId}", methods={"GET"})
     *
     * @OA\Tag(name="Participant")
     *
     * @OA\Response(response=200, description="Participants list")
     *
     * @param int $eventId
     * @return JsonResponse
     */
    public function __invoke(int $eventId): JsonResponse
    {
        /** @var Event|null $event */
        $event = $this->eventManager->findOneBy(['id' => $eventId]);
        if (is_null($event) ) {
            return new JsonResponse(['error_message' => 'The event was not found.'], Response::HTTP_BAD_REQUEST);
        }

        $participants = $this->participantManager->findBy(['event' => $event]);
        $normalizedList = $this->serializer->serialize($participants, 'json');
        return new JsonResponse(json_decode($normalizedList, true), Response::HTTP_OK);
    }
}