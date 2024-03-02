<?php
namespace App\Controller\Participant;

use App\Entity\Event;
use App\Manager\EventManager;
use App\Manager\ParticipantManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
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
        $this->eventManager = $eventManager;
    }

    /**
     * Get participants list by event id
     *
     * @Route("/api/private/participants/{eventId}", methods={"GET"})
     *
     * @OA\Tag(name="Participant")
     *
     * @OA\Response(response=200, description="Participants list by event")
     * @OA\Response(response=400, description="The event was not found.")
     *
     * @param int $eventId
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function __invoke(int $eventId, UserInterface $user): JsonResponse
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